<?php
class Pages extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url', 'form');

		$this->load->model('formation_model', 'formationManager');
		$this->load->model('calendar_model', 'calendarManager');
		$this->load->model('student_model', 'studentManager');
		$this->load->model('annonce_model', 'annonceManager');
		require("Utils.php");
	}

	public function accueil($page='accueil'){
		$data['title'] = ucfirst($page);
		if(isset($this->session->user) && $this->session->user->type=="student" && $page!=="contacts"){
			$page="listAnnonces";

			}
			if(!isset($this->session->user) && $page!=="contacts") redirect("login/view");
			if($page=="403"){
				$directory="errors";
			}else{
				$directory=isset($this->session->user) ?  $this->session->user->folder : "student";
			}
			if( !file_exists(APPPATH.'views/'.$directory.'/'.$page.'.php')){
				$directory="errors";
				$page= $page !== "403" ?"404" : "403";
			}

			$data=array();
			if(isset($this->session->user)){
				if($this->session->user->type!=="admin"){
					$data=$this->getStudentData($this->session->user->type);
					$data['title'] = $this->session->user->type=="student" ? "Annonces" : "Calendrier des entretiens";
				}else{
					$data=$this->getAdminData();
				}
			}
			$data["addressDefault"]=$this->studentManager->getDefaultAddress()[0];

			$this->load->view('templates/header', $data);
			$this->load->view($directory.'/'.$page, $data);

			if(isset($this->session->user->type) && $this->session->user->type==="student"){
				$this->load->view('js/openMenu', $data);
				$this->load->view('js/scriptAnnonces', $data);
			}
			$this->load->view('templates/footer', $data);

	}

	private function getStudentData($type){
		$data['student']=$this->session->user;
		$data['formations'] = $this->formationManager->getAllRelationsForOneStudent($this->session->user->type, $this->session->user->id);
		if($type=="candidate"){

			foreach($data['formations'] as $formation){
				if($formation->meeting = $this->calendarManager->getOne($this->session->user->type, $this->session->user->id, $formation->id)){
					$date=explode(" ", $formation->meeting->dateRV);
					$fullDate=Utils::getFullDate($date[0]);
					$hour=explode(':',$date[1]);
					$hour=$hour[0].'h'.$hour[1];
					$formation->meeting->canChange=Utils::canStillChange($date[0]);
					$formation->meeting->dateRV=$fullDate." à ".$hour;

				}
			}
			$data["subtitle"] = count($data['formations'])==1 ? ( $this->session->user->type=="candidate" ? "Votre candidature" : "Votre formation" ) : "Vos candidatures" ;
		}else{
			$data["subtitle"]="Offres d'emplois de nos entreprises partenaires";
			$data["annonces"]=$this->annonceManager->getAllByFormationWidthStudents($data['formations'][0]->id, false);
			foreach($data["annonces"] as $type){
				foreach($type as $annonce){
				$annonce->response=$this->annonceManager->getResponsesByCandidate($annonce->id, $this->session->user->id);
				}
			}


		}

		return $data;
	}

	private function getAdminData(){
		$type="candidate";
		$candidate=array(
			"candidats"=>array(
				"nb"=>$this->studentManager->countStudent($type),
				"class"=>"",
				"href"=>site_url("student/view/candidate")
			),
			"candidatures"=>array(
				"nb"=>$this->formationManager->countAllRelations($type),
				"class"=>"",
				"href"=>site_url("student/view/candidate")
			),
			"sont positionnés"=>array(
				"nb"=>$this->formationManager->countStudentsStatus($type, 3,4),
				"class"=>"green",
				"href"=>site_url("student/view/candidate/3/4")
			),
			"non positionnés"=>array(
				"nb"=>$this->formationManager->countStudentsStatus($type, 2),
				"class"=>"orange",
				"href"=>site_url("student/view/candidate/2")
			),
			"en attente d'email"=>array(
				"nb"=>$this->formationManager->countStudentsStatus($type, 1,4),
				"class"=>"orange",
				"href"=>site_url("student/view/candidate/1/4")
			),
			"problèmes"=>array(
				"nb"=>$this->formationManager->countStudentsStatus($type, 5),
				"class"=>"red",
				"href"=>site_url("student/view/candidate/5")
			),
			"entretiens annulés"=>array(
				"nb"=>$this->formationManager->countStudentsStatus($type, 6),
				"class"=>"red",
				"href"=>site_url("student/view/candidate/6")
			),
			"désinscriptions"=>array(
				"nb"=>$this->formationManager->countStudentsStatus($type, 7),
				"class"=>"gray",
				"href"=>site_url("student/view/candidate/7")
			)
		);
		$type="student";
		$student=array(
			"admis"=>array(
				"nb"=>$this->studentManager->countStudent($type),
				"class"=>"",
				"href"=>site_url("student/view/student")
			),
			"inscrits"=>array(
				"nb"=>$this->formationManager->countStudentsStatus($type, 13,14),
				"class"=>"green",
				"href"=>""
			),
			"non inscrits"=>array(
				"nb"=>$this->formationManager->countStudentsStatus($type, 12),
				"class"=>"orange",
				"href"=>""
			),
			"problèmes"=>array(
				"nb"=>$this->formationManager->countStudentsStatus($type, 15),
				"class"=>"red",
				"href"=>""
			),
			"soutenances reportées"=>array(
				"nb"=>$this->formationManager->countStudentsStatus($type, 16),
				"class"=>"red",
				"href"=>""
			)
		);
		$data["formations"] = $this->formationManager->getAll();
		$data["students"]["Entretiens de selection"]=$candidate;
		$data["students"]["Annonces placement"]=$student;
		return $data;
	}

}
