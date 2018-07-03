<?php
class Emailing extends CI_Controller{
	private $problems;
	private $to;
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		if(!isset($this->session->user)) redirect("login/view");
		$this->load->model('formation_model', 'formationManager');
		$this->load->model('student_model', 'studentManager');
		$this->load->model('calendar_model', 'calendarManager');
		require('Mailin_SENDINBLUE.php');
		require('Utils.php');
	}

	private function view($id_formation=null, $email=null){

		if($id_formation==null) redirect("pages/accueil/404");
		//if(empty($this->problems)) redirect("formation/admin/".$id_formation);
		if(!isset($this->session->user->type) || $this->session->user->type!=="admin") redirect("pages/accueil/403");;
		$data['thisForm']=$this->formationManager->getOne('id', $id_formation);
		$data['email']=$email;
		$data['to']=$this->to;
		$data['formations']=$this->formationManager->getAll();
		$data['title'] = "Envoi d'email";
		$data['problems']=$this->problems;
		$this->load->view('templates/header', $data);
		if(!empty($this->problems))	$this->load->view('admin/problems_import');
		if($email!=null) $this->load->view('admin/email_sent');
		$this->load->view('templates/footer', $data);
	}

	public function sendEmail(){
		if(empty($idStudents = $this->input->post('student'))){
			$this->problems.='<br/>Vous devez selectionner des étudiants';
			$this->view($this->input->post('id_formation'));
			return false;
		};
		$this->to="";
		$type=$this->input->post('type');
		$formation=$this->formationManager->getOne("id",$this->input->post('id_formation'));
		$email=null;
		foreach($idStudents as $id){
			if(!empty($this->to)) $this->to.=', ';
			$student = $this->studentManager->getStudentByRelation($type, $id, $formation->id);
			switch($this->input->post('typeEmail')){
				case "prise-de-rendez-vous":
					$old_status=[1];
					$new_status=2;
					$meeting=array("days"=>$this->input->post("days"));
					$go=true;
					$relance=false;
					break;
				case "relance":
					$old_status=[2];
					$new_status=2;
					$meeting=array("days"=>$this->input->post("days"));
					$go=true;
					$relance=true;
					break;
				case "rappel":
					$old_status=[3,4];
					$new_status=3;
					$meeting=$this->calendarManager->getOne($type, $id, $formation->id);
					$go=true;
					$relance=false;
					break;
				case "precision-salle":
					$old_status=[4];
					$new_status=3;
					$meeting=$this->calendarManager->getOne($type, $id, $formation->id);
					$go = ($meeting->location!=="" && $meeting->location!==null) ? true : false;
					$relance=false;
					break;
				case "erratum-session-reportee":
					$old_status=[5,6];
					$new_status=2;
					$meeting=$this->calendarManager->getOne($type, $id, $formation->id);
					$go=true;
					$relance=false;
					break;
			}
			if($go){
				foreach($old_status as $stat){
					if($student->id_status==$stat){
						$error=false;
						$this->to .= $student->name;
						if($email=$this->sendMessage($this->input->post('typeEmail'), $formation, $meeting, $student)){
							if(!$relance){
								$this->formationManager->updateStatus($type, $id, $formation->id, $new_status);
							}else{
								$this->formationManager->increaseRelance($type, $id, $formation->id, $student->relance);
							}
						}else{
							$this->problems.='<br/>'.$student->name.' '.$student->firstname.' : Message non envoyé.';
						}
						break;
					}else{$error=true;}

				}
			}else{ $error=true;}
			if($error) $this->problems.='<br/>'.$student->name.' '.$student->firstname.' n\'est pas concerné par ce type d\'information.';
		}
		$this->view($formation->id, $email);
	}
	public function sendEmailProblem(){
		$this->formationManager->updateStatus($this->session->user->type, $this->session->user->id, $this->input->post("id_formation"), 5);
		$referends=$this->formationManager->getReferends($this->input->post("id_formation"));

		foreach($referends as $admin){

			$email=$this->sendMessage($this->input->post('typeEmail'), $this->formationManager->getOne("id", $this->input->post("id_formation")), null, $this->session->user, $admin, $this->input->post("message"));
			$this->session->user->message="Votre message a été envoyé. Nous vous recontacterons prochainement.";
		}

		redirect("pages/accueil");

	}

	public function sendEmailAuto($type=null, $id_student=null, $id_formation=null, $fromAdmin=false, $cancel=false){
		if($type && $id_student && $id_formation){
			$student=$this->studentManager->getOne($type, "id", $id_student);
			$student->formation=$id_formation;
			$student->type=$type;
		}else{
			$student=$this->session->user;
		}
		if($cancel){
			$msgAdmin="annulation";
			$msgStudent="annulationStudent";
			$meeting=null;
			$distant= "";
		}else{
			$msgAdmin="nouveau-positionnement-entretiens";
			$msgStudent="confirmation-entretien";

			$meeting=$this->calendarManager->getOne($student->type, $student->id, $student->formation);
			$distant= $meeting->distant=="" ? false : true;
		}
		$formation=$this->formationManager->getOne("id", $student->formation);


		if($email=$this->sendMessage($msgStudent, $formation, $meeting, $student, null,null, $distant)){
			$student->message="Merci pour votre positionnement. Vous recevrez sous peu un courriel récapitulatif.";
		}else{
			$student->message="Nous n'avons pas pu vous envoyer d'email récapitulatif.";
		}
		$referends=$this->formationManager->getReferends($student->formation);

		foreach($referends as $admin){
			$this->sendMessage($msgAdmin, $formation, $meeting, $student,$admin, null, $distant);
		}

		if($fromAdmin){
			$this->session->lastAction="meetingStudent";
			$this->session->message="Le calendrier a été modifié";
			redirect("student/casParticulier/".$type."/".$id_student);
		}
		redirect("pages/accueil");
	}

	private function sendMessage($typeEmail, $formation, $meeting, $student, $admin=null, $message=null){
		// A CHANGER
		$apiKey="XXXXXXXXXXXXXXXXXX";
		$mailin = new Mailin('https://api.sendinblue.com/v2.0',$apiKey);
		$attachment=array();
		$stillAvailable=10;
		if($admin){
			$stillAvailable=$this->calendarManager->countCalendarStillAvailable($student->type,$formation->id);
		}
		if($typeEmail=="confirmation-entretien" || $typeEmail=="precision-salle" || $typeEmail=="rappel"){
			$attachment=array(base_url()."img/Plan_CFA_des_Sciences.png");
		}
		require('./emails/'.$typeEmail.'.php');

		$to= $admin ? $admin->email : $student->email;

		$data = array( "to" => array( $to =>"Destinataire"),
			"from" => array("ne-pas-repondre@cfa-sciences.fr","CFA des SCIENCES"),
			"replyto" => array("ne-pas-repondre@cfa-sciences.fr","Message automatique"),
			"subject" => $subject,
			"text" => $message_txt,
			"html" => $message_html,
			"headers" => array("Content-Type"=> "text/html; charset=iso-8859-1"),
			"attachment" => $attachment

		);
		// A COMMENTER
		return $data;

		if($mailin->send_email($data)["code"]=="success") return $data;
		return false;
	}
}
