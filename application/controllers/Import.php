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

	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		if(!isset($this->session->user)) redirect("login/view");
		$this->load->model('formation_model', 'formationManager');
		$this->load->model('student_model', 'studentManager');
		$this->load->model('calendar_model', 'calendarManager');
		$this->load->model('annonce_model', 'annonceManager');
	}
	private function view(){
		if(!isset($this->session->user->type) || $this->session->user->type!=="admin") redirect("pages/accueil/403");
		if(empty($this->problems)) redirect('student/view');

		$data['problems']=$this->problems;
		$data['title']="L'importation ne s'est pas bien passé...";
		$data["formations"] = $this->formationManager->getAll();

		$this->load->view('templates/header', $data);
		$this->load->view('admin/problems_import', $data);
		$this->load->view('forms/importStudents', $data);
		$this->load->view('templates/footer', $data);

	}
	public function createFormations($newFormation=null, $type=null){
		$data["newFormation"]=$newFormation;
		$data["formations"] = $this->formationManager->getAll();
		$data["type"]=$type;
		$data['referents']=$this->studentManager->getStaffs("admin");
		$data['staffpart']=$this->studentManager->getStaffs("staffpart");
		$this->load->helper('form');
		$this->load->view('templates/header', $data);

		if($newFormation===null){
			$ypareo=$this->input->post("ypareo");
			for($i=0; $i<count($ypareo); $i++){
				$nameFormation= $this->input->post("formation".$i)!=="" ? $this->input->post("formation".$i) : $ypareo[$i];
				$urlFormation=$this->input->post("url".$i)!=="" ? $this->input->post("url".$i) : null;
				$id_formation=$this->formationManager->createOne($ypareo[$i], $nameFormation, $urlFormation);
				$admin=$this->input->post($i.'_admin[]');
				$staffpart=$this->input->post($i.'_staffpart');
				for($j=0; $j<count($admin);$j++){
					if($admin[$j]!==""){
						 $this->studentManager->addReferend($admin[$j], $id_formation, "admin");
					}
				}
				for($j=0; $j<count($staffpart);$j++){
					if($staffpart[$j]!==""){
						 $this->studentManager->addReferend($staffpart[$j], $id_formation, "staffpart");
					}
				}
			}
			$this->createStudent($this->input->post('type'));
		}else{

			$this->load->view('forms/createFormations', $data);
		}
		$this->load->view('templates/footer', $data);
	}
	public function importStudent(){
		$students=array();
		$this->problems="";
		if(isset($_FILES['fileRV']) && ($file = fopen($_FILES['fileRV']['tmp_name'], 'r')) !== false){
			$row =1;
			while(($lign = fgetcsv($file,10000, ";"))!== false){
				$lign=array_map("utf8_encode", $lign);
				if($row==1){
					if(!$this->testHeader($lign)) break;
				}else if($this->testLign($lign, $row)){
					$studentLign=array(
						"student"=>array(
							"name"=>$lign[$this->template["NOM_APPRENANT"]],
							"firstname"=>$lign[$this->template["PRENOM_APPRENANT"]],
							"email"=>$lign[$this->template["EMAIL_COURRIER"]],
							"phone"=>$lign[$this->template["PORTABLE_COURRIER"]],
							"date_candidature"=>date('Y'),
							"password"=>$this->createPassword()
							),
						"formation"=>$lign[$this->template["NOM_FORMATION_SOUHAITE"]]

				);
					array_push($students, $studentLign);

				}
				$row++;
			}
			fclose($file);

			$newFormation=array();
			foreach($students as $student){
				if(!$this->formationManager->getOne("ypareo", '"'.$student["formation"].'"')){
					$exist=false;
					for($i=0; $i<count($newFormation); $i++){
						if($newFormation[$i] == $student["formation"]){
							$exist=true;
							break;
						}
					}
					if(!$exist) array_push($newFormation, $student["formation"]);
				}
			}
			$this->session->students=$students;
			if(!empty($newFormation)){
				$this->createFormations($newFormation, $this->input->post('type'));
			}else{

				$this->createStudent($this->input->post('type'));
			}

		}else{
			$this->problems .= "<br/>Le fichier n'a pas pu être importé.";
        }

	}

	private function createStudent($type){
		foreach($this->session->students as $student){
			$id=$this->studentManager->getOne($type, "email", '"'.$student["student"]["email"].'"', 'id');

			if(!(isset($id))){
				if($type=="student"){
					$oldCandidate=$this->studentManager->getOne("candidate", "email", '"'.$student["student"]["email"].'"');
					if($oldCandidate!==null){
						$this->studentManager->deleteStudent("candidate", $oldCandidate->id);
						$student["student"]["password"]=$oldCandidate->password;
					}else{
						$student["student"]["password"]=$this->createPassword();
						// s'il y a des annonces
							//creation table réponses
							//Envoi email avec code
					}

				}
				$id=$this->studentManager->createNewStudent($type, $student["student"]);

			}
			$this->createJoin($student["formation"], $id, $type);

		}
		if(empty($this->problems)){
			$this->session->lastAction="importStudent";
			$this->session->message="Le fichier a bien été importé.";

		}else{
			$this->session->lastAction="importStudent";
			$this->session->message=$this->problems;
		}
		if($type=="student") $this->testAnnonces($this->formationManager->getOne("ypareo", '"'.$student["formation"].'"', 'id'), $id);
		redirect("student/view/".$type);
	}

	private function createJoin($formation, $id, $type){
		$goOn=true;
		if($type=="student" && $this->formationManager->getAllRelationsForOneStudent($type, $id)!=null) $goOn=false;
		if($goOn){
			$id_formation=$this->formationManager->getOne("ypareo", '"'.$formation.'"', 'id');
			if($id_formation==null){
				$this->problems.="</br> probleme formation : ".$formation;
				return false;
			}
			if(!empty($this->formationManager->getOneRelation($type, $id, $id_formation))){
				$this->problems.="<br/> Candidature déjà prise en compte";
			}else{
				 $this->formationManager->createNewRelation($type,$id, $id_formation, $this->calendarManager->selectAllByFormation($type, $id_formation));
			}
		}else{
			$this->problems.="<br/>Admis déjà inscrit";
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


	private function testAnnonces($id_formation, $id_student){
		$annonces=$this->annonceManager->getAllByFormation($id_formation);
		if(count($annonces)>0){
			foreach($annonces as $annonce){
				$this->annonceManager->whiteList($annonce->id, $id_student);
			}
			redirect("emailing/sendEmailFirstAnnonce/".$id_student.'/'.$id_formation);
		}

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
