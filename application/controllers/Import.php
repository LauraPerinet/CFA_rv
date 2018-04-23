<?php
class Import extends CI_Controller{
	private $template=[
				"PORTABLE_COURRIER"=>"",
                "NOM_FORMATION_SOUHAITE"=>"",
                "NOM_APPRENANT"=>"",
                "PRENOM_APPRENANT"=>"",
                "EMAIL_COURRIER"=>""
        ];
	private $problems;
	
	private function view(){
		$this->load->helper('url');
		if(!isset($this->session->user)) redirect("login/view");
		if(!isset($this->session->user->type) || $this->session->user->type!=="admin") show_403();
		if(empty($this->problems)) redirect('student/view');
		
		
		$data['problems']=$this->problems;
		$data['title']="L'importation ne s'est pas bien passé...";
		$data["formations"] = $this->db->query("SELECT * FROM formation")->result();
		
		$this->load->view('templates/header', $data);
		$this->load->view('admin/problems_import', $data);
		$this->load->view('forms/importStudents', $data);
		$this->load->view('templates/footer', $data);
		
	}
	public function importStudent(){
		$this->problems="";
		if(isset($_FILES['fileRV']) && ($file = fopen($_FILES['fileRV']['tmp_name'], 'r')) !== false){
			$row =1;
			while(($lign = fgetcsv($file,10000, ";"))!== false){
				$lign=array_map("utf8_encode", $lign);
				if($row==1){
					if(!$this->testHeader($lign)) break;
				}else if($this->testLign($lign, $row)){
					$this->createStudent($lign, $this->input->post('type'));
				}
				$row++;
			}
			fclose($file);
			
		}else{
			$this->problems .= "<br/>Le fichier n'a pas pu être importé.";
        }
		if(empty($this->problems)){
			$this->load->helper('url');
			redirect("student/view");
		}
		$this->view();
	}
	
	private function createStudent($lign, $type){
		$query=$this->db->query('SELECT id FROM '.$type.' WHERE email ="'.$lign[$this->template["EMAIL_COURRIER"]].'"');
		if(!(isset($query->row()->id))){
			$data=array(
					"name"=>$lign[$this->template["NOM_APPRENANT"]],
					"firstname"=>$lign[$this->template["PRENOM_APPRENANT"]],
					"email"=>$lign[$this->template["EMAIL_COURRIER"]],
					"phone"=>$lign[$this->template["PORTABLE_COURRIER"]],
					"date_candidature"=>date('Y'),
					"password"=>$this->createPassword(),
					
				);
			$this->db->insert( $type , $data);
			$id=$this->db->query('SELECT id FROM '.$type.' WHERE email ="'.$lign[$this->template["EMAIL_COURRIER"]].'"')->row()->id;
		}else{
			$id=$query->row()->id;
		}
		
		$this->createJoin($lign[$this->template["NOM_FORMATION_SOUHAITE"]], $id, $type);
	}
	
	private function createJoin($formation, $id, $type){
		if(!isset($this->db->query('SELECT id FROM formation WHERE ypareo ="'.$formation.'"')->row()->id)){
			$this->problems.="</br> probleme formation : ".$formation;
			return false;
		}else{
			$id_formation=$this->db->query('SELECT id FROM formation WHERE ypareo ="'.$formation.'"')->row()->id;
		}
		if(!empty($test=$this->db->query('SELECT * FROM '.$type.'_formation WHERE id_'.$type.'='.$id.' AND id_formation='.$id_formation)->result())){
			$this->problems.="<br/> Candidature déjà prise en compte";
		}else{
			 
			$data=array(
					'id_'.$type=>$id,
					'id_formation'=>$id_formation,
					'id_status'=> empty($this->db->query('SELECT * FROM '.$type.'_calendar WHERE id_formation ='.$id_formation)->result()) ? ($type=="candidate" ? 0 : 10) : 1
			);
			$this->db->insert($type.'_formation', $data);
		}
	}

		
	private function testHeader($lign){
		for($i=0; $i<count($lign);$i++){
			if(isset($this->template[$lign[$i]])){
				$this->template[$lign[$i]]=$i;
			}
		}
		foreach($this->template as $key=>$value){
			if($value === ""){
				$this->problems.="<br/>Erreur fichier importé : manque colonne ".$key;
				return false;
			}
		}

		return true;
	}
	
	private function testLign($lign, $row){
		if(empty($lign))return true;
		foreach($this->template as $key=>$header){
			
			if($lign[$header]=="" | $lign[$header]==" "){
				$this->problems .="<br/>Ligne $row : le champs $key est vide";
				return false;
			}
		}
		// si le champs == email, on vérifie qu'il y a un arrobase
		if(!strpos($lign[$this->template["EMAIL_COURRIER"]], "@")){
			$this->problems.="<br/>Ligne $row : le champs email est incorrect.<br/>";
			return false;
		}
		return true;
    }
	


	private function createPassword(){
		$alphabet=["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
		$password="";
		while(strlen($password)<6){
			$password.=$alphabet[rand(0,count($alphabet)-1)];
		}
		return $password;
	}
}