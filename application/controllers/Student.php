<?php
class Student extends CI_Controller{
	public function view(){
		
		$this->load->helper('url');
	if(!isset($this->session->user)) redirect("login/view");
		if(!isset($this->session->user->type) || $this->session->user->type!=="admin") show_403();
			
		$this->load->library('form_validation');

		$data=$this->getData();
		
		$this->load->view('templates/header', $data);
		$this->load->view('forms/importStudents', $data);
		$this->load->view('forms/selectionStudents', $data);
		$this->load->view('admin/student_table', $data);
		$this->load->view('templates/footer', $data);
		$this->load->view('js/showPopUp', $data);
	}
	
	public function deleteStudent(){
		if($this->input->post('student') && !empty($this->input->post('student'))){
			$type=$this->input->post('type');
			foreach($this->input->post('student') as $studentId) {
				$this->db->query('DELETE FROM '.$type.' WHERE id='.$studentId);
				$this->db->query('DELETE FROM '.$type.'_formation WHERE id_'.$type.'='.$studentId);
			}
		};
		$this->load->helper('url');
		redirect("student/view");
	}
	
	private function createQuery($query='SELECT * FROM candidate', $formation=null, $year=null, $type="candidate"){
		if($formation | $year | $type) $query.=' where ';
		if($formation){
			$query.='id IN ( SELECT id_'.$type.' FROM '.$type.'_formation WHERE id_formation='.$formation.')';
			if($year) $query.=' and';
		}
		if($year ) $query.=" date_candidature=".$year;
		
		return $query;
	}
	
	private function getData(){
		
		$data['subtitle']="Candidats";
		$type= $this->input->post('type') ? $this->input->post('type') : "candidate";
		$query='SELECT * FROM '.$type;
		if($this->input->post('formation') || $this->input->post('year')|| $this->input->post('type')){ 
			$query=$this->createQuery(
				$query, 
				$data["query"]["formation"] = $this->input->post('formation') == "null" ? null : $this->input->post('formation'), 
				$data["query"]["year"]=$this->input->post('year')=="null" ? null: $this->input->post('year'), 
				$data["query"]["type"]=$type
			);
			$data['subtitle'] = $this->createSubtitle($data, $type);
		}
		$query=$this->db->query($query);
		
		$data['students']=$query->result();
		
		foreach($data['students'] as $student){
			$student->type=$type;
			$student->formations = $this->db->query("SELECT * FROM formation WHERE id IN (SELECT id_formation FROM ".$type."_formation WHERE id_".$type."=".$student->id.")")->result();
		}
		
		$data['title']="Gestion des apprentis";
		$data['formations']=$this->db->query('SELECT * FROM formation')->result();
		$data['minYear']=$this->db->query('SELECT MIN(date_candidature) AS minYear FROM '.$type)->row()->minYear;
		
		return $data;
	}
	private function createSubtitle($data, $type){
		if($type) $data['subtitle'] = $type=="student" ? "Apprentis" : "Candidats";
		if($data["query"]["formation"]) $data['subtitle'].=" | ".$this->db->query('SELECT ypareo FROM formation WHERE id='.$data["query"]["formation"])->row()->ypareo;
		if($data["query"]["year"]) $data['subtitle'].=" | ".$data["query"]["year"];
		
		return $data['subtitle'];
	}
}