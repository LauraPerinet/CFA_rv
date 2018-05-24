<?php
class Student extends CI_Controller{
	private $student;
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->model('student_model', 'studentManager');
		$this->load->model('formation_model', 'formationManager');
		$this->load->model('calendar_model', 'calendarManager');
		if(!isset($this->session->user)) redirect("login/view");
		if(!isset($this->session->user->type) || $this->session->user->type!=="admin") redirect("pages/accueil/403");
		require("Utils.php");
		
	}
	
	public function view($type="candidate", $id_status=null, $id_status2=null){
		$data=$this->getData($type, $id_status, $id_status2);

		$this->load->view('templates/header', $data);
		if($this->session->lastAction=="importStudent" || $this->session->lastAction=="deleteStudent") $this->load->view('templates/msgSent');
		
		$this->load->view('forms/importStudents', $data);
		$this->load->view('forms/selectionStudents', $data);
		$this->load->view('admin/student_table', $data);
			$this->load->view('js/showPopUp', $data);
		$this->load->view('js/openMenu', $data);
		$this->load->view('templates/footer', $data);
	
	}
	
	public function casParticulier($type=null, $id=null){
		if(!$id || !$type) redirect("pages/accueil");
		$student=$this->studentManager->getOne($type, "id", $id);
		$student->formations = $this->studentManager->getStudentFormations($type, $student->id);
		$data["title"]=$student->firstname." ".$student->name;
		$data['type']=$type;
		$data["student"]=$student;
		$data["formations"]=$this->formationManager->getAll();
		foreach($data["formations"] as $formation){
			$data['calendars'][$formation->ypareo] =$this->getMeetings($formation->id, $type);
		}
		$this->load->view('templates/header', $data);
		if($this->session->lastAction=="updateStudent" || $this->session->lastAction=="meetingStudent" ){ $this->load->view('templates/msgSent'); }
		$this->load->view('forms/studentForm', $data);
		$this->load->view('admin/studentFormations', $data);
		$this->load->view('js/openMenu', $data);
		$this->load->view('templates/footer', $data);
	}
	
	public function deleteStudent(){
		if($this->input->post('student') && !empty($this->input->post('student'))){
			foreach($this->input->post('student') as $studentId) {
				//if(empty($this->calendarManager->getAllByStudent($this->input->post('type'), $studentId))) 
				$this->studentManager->deleteStudent($this->input->post('type'), $studentId);
			}
		};
		$this->session->lastAction="deleteStudent";
		$this->session->message="Les étudiants ont bien été supprimés";
		redirect("student/view");
	}

	public function update($id_student){
		$email=$this->input->post("email");
		$phone=$this->input->post("phone");
		$this->studentManager->updateStudent($this->input->post('type'), $id_student, $email, $phone);
		$this->session->lastAction="updateStudent";
		$this->session->message="Le ".$this->input->post('type')." a été mis à jour";
		redirect("student/casParticulier/candidate/".$id_student);
	}
	private function getData($type, $id_status, $id_status2){
		$data['subtitle']=$type=="candidate" ? "Candidats" : "Apprentis";
		if($id_status==null){
			$id_status=$this->input->post('status')=="all" ? null :$this->input->post('status');
		}
		$data['query']=array(
			"formation"=>$this->input->post('formation') == "all" ? null : $this->input->post('formation'),
			"year"=>$this->input->post('year')=="all" ? null: $this->input->post('year'),
			"status"=>$id_status,
			"type"=>$this->input->post('type') ? $this->input->post('type') : $type
		);

		$data['type']= $this->input->post('type') ? $this->input->post('type') : "candidate";
		$data['students']=$this->studentManager->selectStudentsBy($data['query']);
		$data['status']=$this->formationManager->getStatus($type);
		$data['subtitle'] = $this->createSubtitle($data);
		foreach($data['students'] as $student){
			$student->type=$data["query"]["type"];
			$student->formations = $this->studentManager->getStudentFormations($student->type, $student->id, $data["query"]["status"], $id_status2);
		}
		$data['title']="Gestion des apprentis";
		$data['formations']=$this->formationManager->getAll();
		$data['minYear']=$this->studentManager->getMinYear($data['query']['type']);
		
		return $data;
	}
	
	private function createSubtitle($data){
		if($data["query"]["type"]) $data['subtitle'] = $data["query"]["type"]=="student" ? "Apprentis" : "Candidats";
		if($data["query"]["formation"]) $data['subtitle'].=" | ".$this->formationManager->getOne("id", $data["query"]["formation"], "ypareo");
		if($data["query"]["year"]) $data['subtitle'].=" | ".$data["query"]["year"];
		
		return $data['subtitle'];
	}
	
	private function getMeetings($id_formation, $type){
		$meetings= array();
		$day="";
		$today=new DateTime();

		foreach($this->calendarManager->selectAllByFormation($type, $id_formation) as $calendar){

			$date=explode(" ", $calendar->dateRV);
			$fullDate;
			if($day!==$date[0]){
				$day=$date[0];
				$fullDate=Utils::getFullDate($day);
				$meetings[$fullDate]=array();
			}
			array_push($meetings[$fullDate], array("id"=>$calendar->id,"hour"=>substr($date[1], 0, 5), "id_student"=> $type=="student" ? $calendar->id_student : $calendar->id_candidate, "location"=>$calendar->location, "skype"=>$calendar->skype ));
		}
		return $meetings;
	}
	
}