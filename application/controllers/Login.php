<?php

class Login extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		//$this->load->library('form_validation');

		$this->load->model('student_model', 'studentManager');
	}

	public function view($page='login'){
		if(isset($this->session->user)) redirect("pages/accueil");
		$data["problems"]="";
		$goIn=false;
		$data['title'] = ucfirst($page);
		//$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		//$this->form_validation->set_rules('password', 'Mot de passe', 'required');
		/*

		if($this->form_validation->run()===true){
			if($this->testConnexion()){ $goIn=true; }else{
				$data["problems"].="<br/>L'email ou le mot de passe est invalide.<br/>";
			}
		}*/
		if($this->input->post("email")!=null){
			if($this->testConnexion()){ $goIn=true; }else{
				$data["problems"].="<br/>L'email ou le mot de passe est invalide.<br/>";
			}
		}

		if($goIn){
			redirect("pages/accueil");
		}else{
			$this->load->view('templates/header', $data);
			$this->load->view('forms/'.$page, $data);
			$this->load->view('templates/footer', $data);
		}

	}

	public function disconnect(){
		session_destroy();
		redirect('login/view');

	}

	private function testConnexion(){
		$email = $this->input->post("email");
		if(!strpos($email, "@cfa-sciences.fr") && !strpos($email, "@cci-paris-idf.fr")){
			$table=$this->input->post('type');
			$password=$this->input->post("password");

		}else{
			$table="admin";
			$password=md5($this->input->post("password"));
		}
		$user=$this->studentManager->getOne($table, "email", '"'.$email.'"');

		if($user!=null && $user->password===$password){
			$this->session->user=$user;
			$this->session->user->type=$table;
			$this->session->user->folder=$table=="admin" ? "admin" : "student";
			return true;
		}else{
			return false;
		}
	}
}
