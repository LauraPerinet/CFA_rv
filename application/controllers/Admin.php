<?php
class Admin extends CI_controller{
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		if(!isset($this->session->user) || $this->session->user->type!=="admin") redirect("login/view");
		$this->load->model('formation_model', 'formationManager');
		$this->load->model('student_model', 'userManager');
		$this->load->model('calendar_model', 'calendarManager');
	}

	public function formations(){
		$data['formations']=$this->formationManager->getAll();
		$data['title']="Modifier ou supprimer les formations";

		$this->load->view('templates/header', $data);
		if(isset($this->session->lastAction) && $this->session->lastAction=="updateFormation"){
			$this->load->view('templates/msgSent');
		}else{$this->session->lastAction="";}
		$this->load->view('forms/formationsManager', $data);
		$this->load->view('templates/footer', $data);
	}
	public function suppressionFormation($data){
		$data["title"]="Suppressions d'une formation";
		$this->load->view('templates/header', $data);
		$this->load->view('admin/deleteFormations', $data);
		$this->load->view('templates/footer', $data);
	}
	public function referents(){
		$data['formations']=$this->formationManager->getAll();
		$data['referents']=$this->formationManager->getAdmins();
		$data['title']="Gestion des utilisateurs référents";

		$this->load->view('templates/header', $data);
		if(isset($this->session->lastAction) && $this->session->lastAction=="updateAdmin"){
			$this->load->view('templates/msgSent');
		}
		$this->load->view('forms/adminManager', $data);
		$this->load->view('templates/footer', $data);
	}


	public function modificationFormation($id_formation=null, $areYouSure=false){
		if(!$id_formation) redirect("admin/formations");
		if($this->input->post("modification")){
			$this->formationManager->updateFormation($id_formation, $this->input->post("formation"));
			$this->session->message="Le nom de la formation ".$this->input->post("formation")." a été modifié.";
			$this->session->lastAction="updateFormation";
		}
		if($this->input->post("erase") || $areYouSure){
			$data=$this->formationManager->testDeleteFormation($id_formation);
			if($areYouSure || $data["total"]==0 ){
				$this->formationManager->deleteFormation($id_formation);
				$this->session->message="La formation a été supprimée.";
				$this->session->lastAction="updateFormation";
			}else{
				$this->session->lastAction="";
				$data['formation']=$id_formation;
				$this->suppressionFormation($data);
			}
		}
		$this->formations();
		//redirect("admin/formations");

	}

	public function deleteAdmin(){
		$this->userManager->deleteAdmin($this->input->post("admin"));
		$this->session->message="L'administrateur a été supprimé.";
		$this->session->lastAction="updateAdmin";
		redirect("admin/referents");
	}

	public function ajoutAdmin(){
		$domain=substr($this->input->post("email"), strpos($this->input->post("email"), "@"));
		if( $domain==="@cfa-sciences.fr" || $domain ==="@cci-paris-idf.fr"){
			$data=array(
				"name"=>$this->input->post("name"),
				"firstname"=>$this->input->post("firstname"),
				"email"=>$this->input->post("email"),
				"password"=>md5($this->input->post("password"))
			);
			$this->userManager->createNewStudent("admin", $data);
			$this->session->message="L'administrateur a été créé.";
		}else{
			$this->session->message="L'administrateur n'a pas pu être créé. Il doit avoir une adresse courriel cfa-sciences.fr ou cci-paris-idf.fr.";
		}

		$this->session->lastAction="updateAdmin";
		redirect("admin/referents");
	}
























}
