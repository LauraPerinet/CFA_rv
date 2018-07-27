<?php
class Admin extends CI_controller{
	private $problems=[];
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
		foreach($data['formations'] as $formation){
			$formation->staff["admin"]=$this->userManager->getStaffsByFormation("admin", $formation->id);
			$formation->staff["staffpart"]=$this->userManager->getStaffsByFormation("staffpart", $formation->id);
		}
		$data['title']="Administration du site";
		$data['referents']=$this->userManager->getStaffs("admin");
		$data['staffpart']=$this->userManager->getStaffs("staffpart");
		$this->load->view('templates/header', $data);
		$data['adminmenu']="formations";
		if(isset($this->session->lastAction) && $this->session->lastAction=="updateFormation"){
			$this->load->view('templates/msgSent');
		}else{$this->session->lastAction="";}
		$this->load->view('admin/openTabsAdmin', $data);
		$this->load->view('forms/formationsFormAdmin', $data);
		$this->load->view('templates/footer', $data);
	}
	public function suppressionFormation(){
		$data=$this->session->problems;
		$data['formations']=$this->formationManager->getAll();
		$data['title']="Administration du site";
		$data['adminmenu']="formations";
		$this->load->view('templates/header', $data);
		$this->load->view('admin/openTabsAdmin', $data);
		$this->load->view('admin/deleteFormations', $data);
		$this->load->view('templates/footer', $data);
	}
	public function equipe(){

		$data['formations']=$this->formationManager->getAll();
		$data['referents']=$this->userManager->getStaffs("admin");
		$data['staffpart']=$this->userManager->getStaffs("staffpart");
		$data['title']="Administration du site";
		$data['adminmenu']="staff";

		$this->load->view('templates/header', $data);
		if(isset($this->session->lastAction) && $this->session->lastAction=="updateAdmin"){
			$this->load->view('templates/msgSent');
		}
		$this->load->view('admin/openTabsAdmin', $data);
		$this->load->view('forms/adminForm', $data);
		$this->load->view('js/openMenu', $data);
		$this->load->view('templates/footer', $data);
	}
	public function majAdmin($id, $type){
		$data['formations']=$this->formationManager->getAll();
		$data['title']="Administration du site";
		$data['adminmenu']="staff";
		$data["admin"]=$this->userManager->getOneStaff($type, $id);
		$data["type"]=$type;
		$this->load->view('templates/header', $data);
		if(isset($this->session->lastAction) && $this->session->lastAction=="updateAdmin"){
			$this->load->view('templates/msgSent');
		}
		$this->load->view('admin/openTabsAdmin', $data);
		$this->load->view('forms/oneAdminForm', $data);
		$this->load->view('templates/footer', $data);
	}
	public function majFormation($id){
		$data['formations']=$this->formationManager->getAll();
		foreach($data['formations'] as $formation){
			if($formation->id==$id){
				$data['thisForm']=$formation;
				break;
			}
		}
		$data['title']="Administration du site";
		$data['adminmenu']="formations";
		$data['admin']=$this->userManager->getStaffs("admin", $id);
		$data['staffpart']=$this->userManager->getStaffs("staffpart", $id);

		$this->load->view('templates/header', $data);
		if(isset($this->session->lastAction) && $this->session->lastAction=="updateAdmin"){
			$this->load->view('templates/msgSent');
		}
		$this->load->view('admin/openTabsAdmin', $data);
		$this->load->view('forms/oneFormationForm', $data);
		$this->load->view('templates/footer', $data);
	}
	public function contacts(){

		$data['formations']=$this->formationManager->getAll();
		$data['title']="Administration du site";
		$data['adminmenu']="contact";

		$this->load->view('templates/header', $data);
		if(isset($this->session->lastAction) && $this->session->lastAction=="updateAdmin"){
			$this->load->view('templates/msgSent');
		}
		$this->load->view('admin/openTabsAdmin', $data);
		$this->load->view('forms/contacts', $data);
		$this->load->view('js/openMenu', $data);
		$this->load->view('templates/footer', $data);
	}

	public function modificationFormation($id_formation=null, $areYouSure=false){

		if(!$id_formation) redirect("admin/formations");
		if($this->input->post("modification")!==null){
			redirect("admin/majFormation/".$id_formation);
		}
		if($this->input->post("erase")!==null || $areYouSure){
			$data=$this->formationManager->testDeleteFormation($id_formation);
			if($areYouSure || $data["total"]==0 ){
				$this->formationManager->deleteFormation($id_formation);
				$this->session->message="La formation a été supprimée.";
				$this->session->lastAction="updateFormation";
			}else{
				$this->session->lastAction="";
				$data['formation']=$id_formation;
				$this->session->problems=$data;
				redirect('admin/suppressionFormation');
			}
		}
		$this->formations();
		//redirect("admin/formations");

	}
	public function changeAdmin($id, $type){
		if($this->input->post("delete")!==null){
			$this->deleteStaffs($id, $type);
		}else{
			redirect("admin/majAdmin/".$id."/".$type);
		}
	}
	public function deleteStaffs($id, $type){
		$this->userManager->deleteStaffs($id, $type);
		$this->session->message="L'administrateur a été supprimé.";
		$this->session->lastAction="updateAdmin";
		redirect("admin/equipe");
	}
	public function updateStaff($id, $type){
		if($this->input->post("adminInfos")!==null){
			$data=array(
				"name"=>$this->input->post("name"),
				"firstname"=>$this->input->post("firstname"),
				"email"=>$this->input->post("email")
			);
			$this->userManager->updateStaff($id, $type, $data);
		}else if($this->input->post("adminFormation")!==null){
				$this->userManager->deleteReferendByStaff($id, $type);
				if(count($this->input->post("formations"))>0){
					foreach($this->input->post("formations") as $id_formation){
						$this->userManager->addReferend($id, $id_formation, $type);
					}
				}
			}
		$this->session->message="L'administrateur a été mis à jours.";
		$this->session->lastAction="updateAdmin";
		redirect("admin/majAdmin/".$id."/".$type);
	}

	public function ajoutAdmin(){
		$typeAdmin=$this->input->post("type");
		if($typeAdmin==="admin") $domain=substr($this->input->post("email"), strpos($this->input->post("email"), "@"));
		if( $typeAdmin==="admin"  && ($domain==="@cfa-sciences.fr" || $domain ==="@cci-paris-idf.fr") || $typeAdmin==="staffpart"){
			$data=array(
				"name"=>strtoupper($this->input->post("name")),
				"firstname"=>ucfirst($this->input->post("firstname")),
				"email"=>$this->input->post("email")

			);
			if($typeAdmin==="admin") $data["password"]=md5($this->input->post("password"));
			$this->userManager->createNewStudent($typeAdmin, $data);
			$this->session->message="L'administrateur a été créé.";
		}else{
			$this->session->message="L'administrateur n'a pas pu être créé. Il doit avoir une adresse courriel cfa-sciences.fr ou cci-paris-idf.fr.";
		}

		$this->session->lastAction="updateAdmin";
		redirect("admin/equipe");
	}
























}
