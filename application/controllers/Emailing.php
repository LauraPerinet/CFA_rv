<?php
class Emailing extends CI_Controller{
	private $problems;
	private $to;
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		require('Mailin_SENDINBLUE.php');
		require('Utils.php');
	}
	private function view($id_formation=null, $email=null){

		//if($id_formation==null) show_404();
		//if(empty($this->problems)) redirect("formation/admin/".$id_formation);
		if(!isset($this->session->user)) redirect("login/view");
		if(!isset($this->session->user->type) || $this->session->user->type!=="admin") show_403();
		
		$data['email']=$email;
		$data['to']=$this->to;
		$data['formations']=$this->db->query("SELECT * FROM formation")->result();
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
		$formation=$this->db->query('SELECT * FROM formation WHERE id='.$this->input->post('id_formation'))->row();
		$email=null;
		foreach($idStudents as $id){
			if(!empty($this->to)) $this->to.=', ';
			$student = $this->db->query("SELECT * FROM ".$type." INNER JOIN ".$type."_formation ON id_".$type."=".$type.".id AND id_formation=".$this->input->post('id_formation')." WHERE id =".$id)->row();
			switch($this->input->post('typeEmail')){
				case "prise-de-rendez-vous":
					$old_status=[1];
					$new_status=2;
					$meeting=array("days"=>$this->input->post("days"));
					break;
				case "precision-salle":
					$old_status=[4];
					$new_status=3;
					$meeting=$this->db->query("SELECT * FROM ".$type."_calendar WHERE id_".$type."=".$id." AND id_formation=".$formation->id)->row();
					break;
				case "erratum-session-reportee":
					$old_status=[5,6];
					$new_status=2;
					$meeting=$this->db->query("SELECT * FROM ".$type."_calendar WHERE id_".$type."=".$id." AND id_formation=".$formation->id)->row();
					break;
			}
			
			foreach($old_status as $stat){
				if($student->id_status==$stat){
					$error=false;
					$this->to .= $student->name;
					if($email=$this->sendMessage($this->input->post('typeEmail'), $formation, $meeting, $student)){
						$this->db->query("UPDATE ".$type."_formation SET id_status=".$new_status." WHERE id_".$type."=".$id." AND id_formation=".$formation->id);
					}else{
						$this->problems.='<br/>'.$student->name.' '.$student->firstname.' : Message non envoyé.';
					}
					break;
				}else{$error=true;}
				
			}	
			if($error) $this->problems.='<br/>'.$student->name.' '.$student->firstname.' n\'est pas concerné par ce type d\'information.';
		}
		$this->view($formation->id, $email);
		
	}
	
	public function sendEmailAuto(){
		$student=$this->session->user;
		$formation=$this->db->query("select * from formation where id=".$student->formation)->row();
		$meeting=$this->db->query("SELECT * FROM ".$student->type."_calendar WHERE id_".$student->type."=".$student->id." AND id_formation=".$student->formation)->row();
		if($email=$this->sendMessage("confirmation-entretien", $formation, $meeting, $student)){
			$student->message=$email['html'];
		}else{
			$student->message="Nous n'avons pas pu vous envoyer d'email récapitulatif.";
		}
		$referends=$this->db->query("SELECT * FROM admin, admin_formation where id_formation=".$formation->id)->result();
		
		foreach($referends as $admin){
			$this->sendMessage("nouveau-positionnement-entretiens", $formation, $meeting, $student,$admin);
		}
		redirect("pages/accueil");
	}

	private function sendMessage($typeEmail, $formation, $meeting, $student, $admin=null){
		// A CHANGER
		$apiKey="XXXXXXXXXXXXXXXXXX";
		$mailin = new Mailin('https://api.sendinblue.com/v2.0',$apiKey);
		require('./emails/'.$typeEmail.'.php');

		$to= $admin ? $admin->email : $student->email;
		
		$data = array( "to" => array( $to =>"Destinataire"),
			"from" => array("ne-pas-repondre@cfa-sciences.fr","CFA des SCIENCES"),
			"replyto" => array("ne-pas-repondre@cfa-sciences.fr","Message automatique"),
			"subject" => $subject,
			"text" => $message_txt,
			"html" => $message_html,
			"headers" => array("Content-Type"=> "text/html; charset=iso-8859-1"),
			
		);

		return $data;
		if($mailin->send_email($data)["code"]=="success") return $data; 
		// A CHANGER
		 
		return false;
	}
}