<?php
class Annonce extends CI_controller{
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		if(!isset($this->session->user)) redirect("login/view");
		$this->load->model('formation_model', 'formationManager');
		$this->load->model('annonce_model', 'annonceManager');
		$this->load->model('student_model', 'userManager');
	}
	public function delete($id_formation){
		if($this->session->user->type!=="admin") redirect("login/view");
		$id_annonce=$this->input->post("id_annonce");
		$this->annonceManager->delete($id_annonce);
		$this->session->message= $id_annonce=="all"?"Les annonces ont été supprimées":"L'annonce est supprimée.";
		$this->session->lastAction="createAnnonce";
		redirect("formation/admin/$id_formation/student");

	}
	public function create(){
		if($this->session->user->type!=="admin") redirect("login/view");
		$id_formation=$this->input->post('id_formation');
		$data=array(
			"title"=>$this->input->post("title"),
			"text"=>nl2br(htmlentities($this->input->post("text"))),
			"expiration"=>$this->input->post("date"),
			"id_formation"=>$id_formation
		);

		$id=$this->annonceManager->create($data);
		$blackList=$this->input->post("blackList[]");
		if(count($blackList)>0){
			$this->annonceManager->blackList($id, $blackList);
			$whiteList=array_values(array_diff($this->userManager->getAllIdByFormation("student", $id_formation, 10), $blackList));
		}else{$whiteList=$this->userManager->getAllIdByFormation("student", $id_formation, 10);}
		$this->annonceManager->createResponses($id,$whiteList);
		$this->session->message="L'annonce est en ligne et un email a été envoyé aux admis concernés.";

		$this->session->lastAction="createAnnonce";
		redirect("emailing/sendEmailAnnonce/$id/$id_formation");
	}

	public function toWhiteList(){
		if($this->session->user->type!=="admin") redirect("login/view");
		$id_annonce=$this->input->post("id_annonce");
		$id_student=$this->input->post("id_student");
		$id_formation=$this->input->post("id_formation");
		$this->annonceManager->whiteList($id_annonce, $id_student);
		$this->session->message="L'annonce a été envoyé.";

		$this->session->lastAction="createAnnonce";
		redirect("emailing/sendEmailAnnonce/$id_annonce/$id_formation/$id_student");
	}
	public function update(){
		if($this->session->user->type!=="admin") redirect("login/view");
		if($this->input->post('cv')!==null){
			$col="cvSent";
			$value="1";
		}
		if($this->input->post("studentPlace")!==null){
			$col="student";
			$value=$this->input->post("student");
		}
		$this->annonceManager->update($this->input->post("id"), $col, $value);
		redirect("formation/admin/".$this->input->post("id_formation")."/student");
	}
	public function interested(){
		if($this->session->user->type!=="student") redirect("login/view");
		$this->annonceManager->updateInterest($this->input->post("id"), $this->session->user->id, $this->input->post("interested"));
		redirect("pages/accueil");
	}
}
