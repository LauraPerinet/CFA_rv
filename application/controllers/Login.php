<?php

class Login extends CI_Controller {
	public function view($page='login'){
		
		$data['title'] = ucfirst($page);
		
		$this->load->helper(array('form', 'url'));
		if(isset($this->session->user)){
				redirect("pages/view");
		};
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Mot de passe', 'required');
		
		$this->load->view('templates/header', $data);
		
		if($this->form_validation->run()===true){
			if($this->testConnexion()){
				redirect("pages/accueil");
			}else{

				$this->load->view('forms/'.$page, $data);
			}
		}else{
			
			$this->load->view('forms/'.$page, $data);
		}
		
		$this->load->view('templates/footer', $data);
	}
	
	public function disconnect(){
		session_destroy();
		$this->load->helper('url');
		redirect('login/view');
		
	}
	
	private function testConnexion(){
		$email = $this->input->post("email");
		$table = (!strpos($email, "@cfa-sciences.fr") && !strpos($email, "@cci-paris-idf.fr")) ? $this->input->post('type') : "admin";
		
		$query=$this->db->query('SELECT * FROM '.$table.' where email="'.$email.'"' );

		$user=$query->row();
		
		if($user!=null && $user->password===$this->input->post("password")){
			$this->session->user=$user;
			$this->session->user->type=$table;
			$this->session->user->folder=$table=="admin" ? "admin" : "student";
			return true;
		}else{
			return false;
		}
	}
}