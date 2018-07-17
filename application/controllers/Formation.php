<?php
class Formation extends CI_Controller{
	private $problems="";
	private $formations;

	public function __construct(){
		parent::__construct();
		$this->load->helper('url', "form");
		if(!isset($this->session->user)) redirect("login/view");
		$this->load->library('form_validation');

		$this->load->model('formation_model', 'formationManager');
		$this->load->model('calendar_model', 'calendarManager');
		$this->load->model('student_model', 'studentManager');
		$this->load->model('annonce_model', 'annonceManager');
		$this->formations=$this->formationManager->getAll();
		require("Utils.php");
	}

	public function admin($id_formation=null, $type="candidate", $action=null, $id=null){

		if(!isset($this->session->user->type) || $this->session->user->type!=="admin") redirect("pages/accueil/403");;
		if($id_formation==null) redirect("pages/accueil");
		$data['admin']=$this->getAdmins($id_formation);
		$data['problems']=$this->problems;
		$data["formations"]=$this->formations;
		$data["olderModif"]=$this->studentManager->getOlderModif($type);
		if($action=="modifAnnonce" && $id!==null){
			$data["annonce"]=$this->annonceManager->getOne($id, true);
		}
		foreach($this->formations as $formation){
			if($formation->id==$id_formation){
				$data['thisForm']=$formation;
				$data['title']=$formation->ypareo;
				break;
			}
		}

		if($type=="candidate"){
			$data['calendar']=$this->getMeetings($id_formation, $type);
			$subtitle="Candidats";
		}else{
			$data['annonces']=$this->annonceManager->getAllByFormationWidthStudents($id_formation);
			foreach ($data["annonces"]["finish"] as $annonce) {
					if($annonce->student!=='-1'){
						$annonce->student=$this->studentManager->getOne("student", "id", $annonce->student);
					}

			}
			$subtitle="Apprentis";
		}
		//View


		$data['classe']="tabContainer";
		$data['subtitle']=$subtitle;
		$data['type']=$type;

		$data['students']=$this->getStudents($id_formation, $type);
		$data['status']=$this->formationManager->getStatus($type);
		$data['query'][$type.'_status']= $this->input->post($type.'_status')!==null ? $this->input->post($type.'_status') : "";
		$data['olderModif']=$this->studentManager->getOlderModif($type);

		$this->load->view('templates/header', $data);
		if($this->session->lastAction=="createCalendar".$id_formation || $this->session->lastAction=="updateCalendar".$id_formation || $this->session->lastAction=="createAnnonce"){
			$this->load->view('templates/msgSent');
		}else{$this->session->lastAction="";}
		$this->load->view('forms/referents', $data);
		if(!empty($this->problems)) $this->load->view('admin/problems_import');
		$this->load->view('admin/openTabsFormation', $data);
		if($type=="candidate"){
			$this->load->view('forms/importCalendar', $data);
			$this->load->view('admin/calendar', $data);
		}else{
			$this->load->view('forms/createAnnonce', $data);
			$this->load->view('admin/listAnnonces', $data);
		}


		if(!empty($data["students"]) && !empty($data['calendar']))$this->load->view('forms/sendEmail', $data);
		$this->load->view('forms/selectionStudentByFormation', $data);
		$this->load->view('admin/student_table', $data);
		if($type=="candidate"){
			$this->load->view('js/scriptCalendar', $data);
			$this->load->view('js/showPopUp', $data);
		}else{
			$this->load->view('js/scriptBlacklist', $data);
		}

		$this->load->view('js/openMenu', $data);
		if(!empty($data["students"]) && !empty($data['calendar'])) $this->load->view('js/formEmail', $data);
		$this->load->view('templates/footer', $data);


	}

	public function inscription($id_formation=null){
		if($id_formation==null ) redirect("pages/accueil");
		//Test si l'utilisateur s'est déjà positionné et si oui, s'il peut encore le changer.
		if($oldMeeting = $this->calendarManager->getOne($this->session->user->type, $this->session->user->id, $id_formation)){
			if(!Utils::canStillChange(explode(" ",$oldMeeting->dateRV)[0])) redirect("pages/accueil");
		}
		$data['title']= $oldMeeting ? "Modification de votre date d'entretien": "Inscription à une date d'entretien";
		$data['subtitle']= $this->formationManager->getOne('id', $id_formation, 'formation');
		$data['formation']=$id_formation;
		$data['type']=$this->session->user->type;
		$data['calendar']=$this->getMeetings($id_formation, $this->session->user->type, false);
		$data['problems']=$this->problems;

		$this->load->view('templates/header', $data);
		$this->load->view('student/calendar', $data);
		$this->load->view('templates/footer', $data);
	}

	public function contact($id_formation=null){
		if(!$id_formation) redirect("page/accueil");
		$data['title']="Indisponibilité pour l'entretien";
		$data['formation']=$id_formation;
		$this->load->view('templates/header', $data);
		$this->load->view('forms/problemInscription', $data);
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
			if($this->input->post('step')!=-5){
				$step=$this->input->post('step');
				$i=0;
			}else{
				$step=0;
				$i=99;
			}
			while($d<$dmax && $i<100){
				$id=$this->calendarManager->createCalendar($type, $d, $id_formation, $this->input->post('location'), $this->input->post("student"), $this->input->post("distant"));
				$d->add(new DateInterval('PT'.$step.'M'));
				$i++;
			}
			if($this->input->post('step')!=-5){
				$redirect="formation/admin/".$id_formation;
				$status = $type=="candidate" ? 0 : 10;
				$this->formationManager->updateOldStatus($type, $id_formation, $status);
			}else{
				$this->checkInscription(1, $id);
				$redirect="formation/checkInscription/1/".$id;
			}
			$this->session->lastAction="createCalendar".$id_formation;
			$this->session->message="Le calendrier a été créé.";
		}else{
			$this->problems.="Tous les champs sont obligatoires.";
		}
		redirect($redirect);
	}

	public function changeCalendar(){
		$meetings=$this->input->post('meeting');
		$type=$this->input->post('type');
		$formation=$this->input->post('id_formation');

		if($this->input->post('delete')){
			$this->deleteCalendar($meetings, $type, $formation);
			$this->session->message="Les dates ont été supprimées.";
		}else if($this->input->post('changeLocation')){
			$this->changeLocation($meetings, $type, $formation, $this->input->post("location"));
			$this->session->message="La salle a été modifiée.";
		}
		$this->session->lastAction="updateCalendar".$formation;
		redirect("formation/admin/".$formation);
	}

	private function deleteCalendar($meetings, $type, $formation){
		if($meetings){
			foreach($meetings as $meeting) {
				$id_student=$this->calendarManager->getOneById($type, $meeting, 'id_'.$type);
				if($id_student!=="0") {
					$this->formationManager->updateStatus($type, $id_student, $formation, 6);
				}
				$this->calendarManager->deleteCalendar($type, $meeting);
			}
		};
	}

	private function changeLocation($meetings, $type, $formation, $location){
		if($meetings && $location ){
			foreach($meetings as $meeting) {
				$this->calendarManager->updateLocation($type, $meeting, $location);
			}
		}
	}

	private function getMeetings($id_formation, $type, $getParticular=true){
		$meetings= array();
		$day="";
		$today=new DateTime();
		foreach($this->calendarManager->selectAllByFormation($type, $id_formation, $getParticular) as $calendar){
			$date=explode(" ", $calendar->dateRV);
			$fullDate;
			if($day!==$date[0]){
				$day=$date[0];
				$fullDate=Utils::getFullDate($day);
				$meetings[$fullDate]=array();
			}
			array_push($meetings[$fullDate], array("id"=>$calendar->id,"hour"=>substr($date[1], 0, 5), "id_student"=> $type=="student" ? $calendar->id_student : $calendar->id_candidate, "location"=>$calendar->location, "distant"=>$calendar->distant, "particular"=>$calendar->particular ));
		}
		return $meetings;
	}

	private function getStudents($id_formation, $type){
		$status=$this->input->post($type."_status")!=="all" ? $this->input->post($type."_status") : null;
		return $this->studentManager->getAllStudentsByFormation($type, $id_formation, $status, array("from"=>$this->input->post("from"), "to"=>$this->input->post("to")));
	}

	public function adminInscription(){
		if($this->input->post("meeting")) $this->checkInscription(1);
		if($this->input->post("cancelMeeting")){
			$type=$this->input->post("type");

			$id_meeting=$this->input->post('cancelMeeting');

			if($this->calendarManager->getOneById($type, $id_meeting, "particular")==1){
				$this->calendarManager->deleteCalendar($type, $id_meeting);
			}else{
				$this->calendarManager->cancelMeeting($type, $id_meeting);
			}


			$student=$this->studentManager->getOne($type, "id", $this->input->post('student'));
			$this->formationManager->updateStatus($type, $this->input->post('student'), $this->input->post('id_formation'), 7);
			redirect("emailing/sendEmailAuto/".$type."/".$this->input->post('student')."/".$this->input->post('id_formation')."/1/1");

			redirect("student/casParticulier/".$type."/".$this->input->post('student'));
		}
	}
	public function checkInscription($fromAdmin=0, $id_meeting=null){

		$this->problems="";
		$problem="Le rendez-vous n'est plus disponible ! Merci de choisir une autre date";
		$id_meeting=$this->input->post('meeting')!=null ? $this->input->post('meeting') : $id_meeting;

		if($fromAdmin!=0){

			$type=$this->input->post("type");
			$student=$this->studentManager->getOne($type, "id", $this->input->post('student'));
			$redirect="emailing/sendEmailAuto/".$type."/".$student->id."/".$this->input->post("id_formation")."/1";

		}else{
			$student=$this->session->user;
			$type=$student->type;
			$redirect="emailing/sendEmailAuto";
		}

		$meeting=$this->calendarManager->getOneById($type, $id_meeting);

		if($meeting->id_student == 0 || $meeting->id_student == $student->id){

			$distant= $this->input->post("distant")!=="" ? $this->input->post("distant") : "";
			$this->calendarManager->updateStudent($type, $id_meeting, $student->id, $this->input->post('id_formation'), $distant);
			if($this->calendarManager->getOneById($type, $id_meeting)->id_student==$student->id){
				$new_status=3;
				if($meeting->location==="" && $distant!="") $new_status=4;
				$this->formationManager->updateStatus($type, $student->id, $this->input->post('id_formation'), $new_status);
				$student->message="Merci de votre inscription. Vous allez recevoir un email récapitulatif.";
				$this->session->user->formation=$this->input->post("id_formation");
				redirect($redirect);
			}else{
				$this->problems.="statut pas changé";
			}
		}else{
			$this->problems.=$problem;
		}

			$this->inscription($this->input->post("id_formation"));

	}

	public function export($csv=false){
		$type=$this->input->post("type");
		$meetings=array();
		$data["formations"]=$this->formations;
		$data["formationsSelected"]=$this->input->post("id_formation");
		$data["title"]="Exporter les calendriers";
		$data["type"]=$type;
		if($this->input->post("id_formation")!=null){

			foreach($this->input->post("id_formation") as $formation){
				$formation_name=$this->formationManager->getOne('id', $formation, 'ypareo');
				$meetings[$formation_name]=$this->getMeetings($formation, $type);

				foreach($meetings[$formation_name] as $date=>$meeting){
					for($i=0; $i<count($meeting);$i++){
						if($meeting[$i]['id_student']!=="0"){
							$meetings[$formation_name][$date][$i]['student']=$this->studentManager->getOne($type, 'id',$meeting[$i]["id_student"]);
						}
					}
				}
			}
			$data["meetings"]=$meetings;

			$data["imprime"]=true;
			if($csv){
				$this->exportCSV($meetings, $type);
			}

		}
		$this->load->view('templates/header', $data);
		$this->load->view('admin/export', $data);
		$this->load->view('templates/footer', $data);

	}

	public function changeReferend(){
		$id_formation=$this->input->post("id_formation");
		$this->formationManager->deleteReferend($id_formation);
		if(count($this->input->post("admin"))>0){
			foreach($this->input->post("admin") as $admin){
				$this->formationManager->addReferend($admin, $id_formation);
			}
		}
		redirect("formation/admin/".$id_formation);
	}

	private function getAdmins($id_formation){
		return $this->formationManager->getAdmins($id_formation);

	}

	private function exportCSV($meetings, $type){
		header('Content-type: text/csv;charset=UTF-8');
		$filename="CFAexportRV_".$type."_".date('Y-m-d').".csv";
		header('Content-Disposition: attachment; filename="'.$filename.'"');

		// do not cache the file
		header('Pragma: no-cache');
		header('Expires: 0');

		// create a file pointer connected to the output stream
		$file = fopen('php://output', 'w');

		// send the column headers
		fputcsv($file, array("\xEF\xBB\xBF"),";");
		fputcsv($file, array('Formation', 'Date de l\'entretien', 'Heure', 'Salle', 'Nom du candidat', 'Prénom du candidat', 'Email du candidat', 'Telephone du candidat', 'Distant'),";");

		// Sample data. This can be fetched from mysql too
		$data = array();
		foreach($meetings as $formation=>$days){
			foreach($days as $day=>$sessions){
				foreach($sessions as $meeting){
					if(!isset($meeting["student"])) {
						$name="";
						$firstname="";
						$email="";
						$phone="";
					}else{
						$name=$meeting["student"]->name;
						$firstname=$meeting["student"]->firstname;
						$email=$meeting["student"]->email;
						$phone=$meeting["student"]->phone;
					}
					$row=array($formation, $day, $meeting["hour"], $meeting["location"], $name, $firstname, $email, $phone, $meeting["distant"]==""?"":"OUI");

					array_push($data, $row);
				}
			}
		}

		// output each row of the data
		foreach ($data as $row)
		{
		fputcsv($file, $row, ";");
		}

		exit();
	}
}
