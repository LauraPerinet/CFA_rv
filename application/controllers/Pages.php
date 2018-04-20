<?php
class Pages extends CI_Controller {
	public function accueil($page='accueil'){
		
		$this->load->helper('url', 'form');
		if(!isset($this->session->user)){
			redirect("login/view");
		};
		
		$data["formations"] = $this->db->query("SELECT * FROM formation")->result();
		if( !file_exists(APPPATH.'views/'.$this->session->user->folder.'/'.$page.'.php')){
			echo $this->session->user->folder.'/'.$page.'.php';
			show_404();
		} 
		if($this->session->user->type!=="admin") $data=$this->getStudentData();
		
		$data['title'] = ucfirst($page);
		$this->load->view('templates/header', $data);
		$this->load->view($this->session->user->folder.'/'.$page, $data);
		$this->load->view('templates/footer', $data);
	}
	
	private function getStudentData(){
		$data['student']=$this->session->user;
		$data['formations'] = $this->db->query(
			'SELECT * FROM '.$this->session->user->type.'_formation 
			INNER JOIN formation ON '.$this->session->user->type.'_formation.id_formation=formation.id 
			WHERE id_'.$this->session->user->type.'='.$this->session->user->id
		)->result();
		
		$data["subtitle"] = count($data['formations']==1) ? ( $this->session->user->type=="candidate" ? "Votre candidature" : "Votre formation" ) : "Vos candidatures" ;
		return $data;
	}
	
}