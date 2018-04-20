<?php 
class Formation extends CI_Controller{
	private $problems="";
	private $formations;
	
	public function __construct(){
		parent::__construct();
		$this->load->helper('url', "form");
		if(!isset($this->session->user)) redirect("login/view");
		$this->load->library('form_validation');
		$this->formations=$this->db->query("SELECT * FROM formation")->result();
		require("Utils.php");
	}
	
	public function admin($id_formation=null){	
		if(!isset($this->session->user->type) || $this->session->user->type!=="admin") show_403();
		if($id_formation==null) redirect("pages/accueil");	
		$data['admin']=$this->getAdmins($id_formation);
		$data['problems']=$this->problems;
		$data["formations"]=$this->formations;
		foreach($this->formations as $formation){
			if($formation->id==$id_formation){
				$data['thisForm']=$formation;
				$data['title']=$formation->ypareo;
				break;
			}
		}
		$students=array(
			array("name"=>"Candidats", "dbname"=>"candidate"), 
			array("name"=>"Apprentis", "dbname"=>"student")
		);

		//View
		$this->load->view('templates/header', $data);
		$this->load->view('forms/referents', $data);
		if(!empty($this->problems)) $this->load->view('admin/problems_import');
		$this->load->view('admin/openTabsFormation', $data);
		foreach($students as $type){
			$data['classe']="tabContainer";
			$data['subtitle']= $type["name"];
			$data['type']=$type["dbname"];
			$data['calendar']=$this->getMeetings($id_formation, $type["dbname"]);
			$data['students']=$this->getStudents($id_formation, $type["dbname"]);
			$data['status']=$this->db->query('SELECT * FROM status WHERE type="'.$type["dbname"].'"')->result();
			$data['query'][$type["dbname"].'_status']= $this->input->post($type["dbname"].'_status') ? $this->input->post($type["dbname"].'_status') : "";
			$this->load->view('templates/openDiv',$data);
			$this->load->view('forms/importCalendar', $data);
			$this->load->view('admin/calendar', $data);
			$this->load->view('forms/sendEmail', $data);
			$this->load->view('forms/selectionStudentByFormation', $data);
			
			$this->load->view('admin/student_table', $data);
			$this->load->view('templates/closeDiv');
			
		}
		$this->load->view('admin/closeTabsFormation', $data);
		$this->load->view('templates/footer', $data);
		$this->load->view('js/scriptCalendar', $data);

	}
	
	public function inscription($id_formation=null){
		if($id_formation==null) redirect("pages/accueil");
		
		$data['title']= "Inscription";
		$data['subtitle']= "";
		$data['formation']=$id_formation;
		$data['type']=$this->session->user->type;
		$data['calendar']=$this->getMeetings($id_formation, $this->session->user->type);
		
		$this->load->view('templates/header', $data);
		$this->load->view('student/calendar', $data);
		$this->load->view('templates/footer', $data);
	}
	
	public function createCalendar(){
		$this->problems="";
		if(($this->input->post('date')!==null && !empty($this->input->post('date')))
			&& ($this->input->post('hourStart')!==null && !empty($this->input->post('hourStart')))
			&& ($this->input->post('hourStop')!==null && !empty($this->input->post('hourStop')))
			&& ($this->input->post('step')!==null && !empty($this->input->post('step')))
		){
			$d=new DateTime($this->input->post('date').$this->input->post('hourStart'));
			$dmax= new DateTime($this->input->post('date').$this->input->post('hourStop'));
			$type=$this->input->post('type');
			$id_formation=$this->input->post('id_formation');

			$i=0;
			while($d<$dmax && $i<100){
				$data=array(
					"dateRV"=>$d->format('Y-m-d H:i:sP'),
					"id_formation"=>$id_formation,
					"location"=>$this->input->post('location')
				);
				$i++;
				$d->add(new DateInterval('PT'.$this->input->post('step').'M'));
				$this->db->insert($type."_calendar", $data);
			}
			
			if($i>=100) echo "erreur";
			$status = $type=="candidate" ? 0 : 10;
			$this->db->query('UPDATE '.$type.'_formation SET id_status='.($status+1).' WHERE id_status='.$status.' AND id_formation='.$id_formation); 
		}else{ 
			$this->problems.="Tous les champs sont obligatoires.";
		}
		redirect("formation/admin/".$this->input->post('id_formation'));
	}
	
	public function deleteCalendar(){
		if($this->input->post('meeting') && !empty($this->input->post('meeting'))){
			$meetings=$this->input->post('meeting');
			$notAvailable=[];
			$type=$this->input->post('type');
			foreach($meetings as $meeting) {
				if($this->db->query("SELECT id_".$type." FROM ".$type."_calendar WHERE id=".$meeting)->row()->id_candidate!=="0") {
					$this->db->query('DELETE FROM '.$type.'_calendar WHERE id='.$meeting);
					//$this->problems.="<br/>Des étudiants sont déjà inscrits à cette session.";
				}else{
					$this->db->query('DELETE FROM '.$type.'_calendar WHERE id='.$meeting);
				}
				
			}
		};
		redirect("formation/admin/".$this->input->post('id_formation'));
	}

	private function getMeetings($id_formation, $type){
		$meetings= array();
		$day="";
		$today=new DateTime();
		foreach($this->db->query("SELECT * FROM ".$type."_calendar WHERE id_formation=".$id_formation.' ORDER BY dateRV')->result() as $calendar){
			$date=explode(" ", $calendar->dateRV);
			$fullDate;
			if($day!==$date[0]){
				$day=$date[0];
				$fullDate=Utils::getFullDate($day);
				$meetings[$fullDate]=array();
			}
			array_push($meetings[$fullDate], array("id"=>$calendar->id,"hour"=>substr($date[1], 0, 5), "id_student"=> $type=="student" ? $calendar->id_student : $calendar->id_candidate, "location"=>$calendar->location ));
		}
		return $meetings;
	}
	
	
	
	private function getStudents($id_formation, $type){
		$query='SELECT '.$type.'.id, name, firstname, email, phone, date_candidature, id_status, status 
		FROM '.$type.', '.$type.'_formation, status 
		WHERE id_'.$type.'='.$type.'.id 
		AND status.id=id_status AND id_formation='.$id_formation;
		
		if($this->input->post($type.'_status')!==null) $query .= " AND id_status=".$this->input->post($type.'_status') ;
		
		$students=$this->db->query($query )->result();
		return $students;
	}
	
	public function checkInscription(){
		$this->problems="";
		$problem="Le rendez-vous n'est plus disponible ! Merci de choisir une autre date";
		$meeting=$this->input->post('meeting');
		$student=$this->session->user;

		if($this->db->query('SELECT id_'.$student->type.' AS id_student FROM '.$student->type.'_calendar WHERE id='.$meeting)->row()->id_student == 0){
			$this->db->query('UPDATE '.$student->type.'_calendar SET id_'.$student->type.'='.$student->id.' WHERE id='.$meeting);
			if($this->db->query('SELECT id_'.$student->type.' AS id_student FROM '.$student->type.'_calendar WHERE id='.$meeting)->row()->id_student==$student->id){
				$this->db->query('UPDATE '.$student->type.'_formation SET id_status=3 WHERE id_'.$student->type.'='.$student->id.' AND id_formation='.$this->input->post("formation"));
				$student->message="Merci de votre inscription. Vous allez recevoir un email récapitulatif.";
				$this->session->user->formation=$this->input->post("formation");
				redirect("emailing/sendEmailAuto");
			}
		}else{
			$this->problems.=$problem;
		}
		redirect("pages/accueil");
	}
	
	public function export(){
		$type=$this->input->post("type");
		$meetings=array();
		$data["formations"]=$this->formations;
		foreach($this->input->post("formation") as $formation){
			$formation_name=$this->db->query("SELECT ypareo FROM formation WHERE id=".$formation)->row()->ypareo;
			$meetings[$formation_name]=$this->getMeetings($formation, $type);
			foreach($meetings[$formation_name] as $date=>$sessions){
				for($i=0; $i<count($sessions);$i++){
					if($sessions[$i]['id_student']!=="0") $meetings[$formation_name][$date][$i]["student"]=$this->db->query("SELECT * FROM ".$this->input->post('type')." WHERE id=".$sessions[$i]["id_student"])->row();}} 
		}
		$data["meetings"]=$meetings;
		$data["imprime"]=true;
		$this->load->view('templates/header', $data);
		$this->load->view('admin/export', $data);
		
	}
	public function changeReferend(){
		$this->db->query("DELETE FROM admin_formation WHERE id_formation=".$this->input->post("id_formation"));
		if(count($this->input->post("admin"))>0){
			foreach($this->input->post("admin") as $admin){
				$data=array(
					"id_admin"=>$admin,
					"id_formation"=>$this->input->post("id_formation")
				);
				$this->db->insert("admin_formation", $data);
			}
		}
		redirect("formation/admin/".$this->input->post("id_formation"));
	}
	private function getAdmins($id_formation){
		$admins=$this->db->query("SELECT * FROM admin")->result();
		foreach($admins as $admin){
			$admin->isRef=$this->db->query("SELECT * FROM admin_formation WHERE id_admin=".$admin->id." AND id_formation=".$id_formation)->row();
		}
		return $admins;
	}
}





































