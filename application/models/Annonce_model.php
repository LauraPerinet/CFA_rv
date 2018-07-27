<?php
class Annonce_model extends CI_Model{
	public function create($data){
		$this->db->insert("annonce", $data);
		return $this->db->insert_id();
	}
	public function update($id, $col, $value){
		$this->db->query("UPDATE annonce set ".$col."=".$value." where id=".$id);
		if($col==="student") $this->db->query("UPDATE student_formation set id_status=11 where id_student=".$value);
	}
	public function updateInterest($id, $id_student, $value){
		$this->db->query("UPDATE response_annonce set interested='".$value."' where id_annonce=".$id." AND id_student=".$id_student);
	}
	public function blackList($id, $blackList){
		$query="INSERT INTO blacklist ( id_annonce, id_student) VALUES";
		for($i=0; $i<count($blackList) ; $i++){
				$query.=" (".$id.", ".$blackList[$i].")";
				if($i<count($blackList)-1) $query.=',';
		}
		$this->db->query($query);
	}
	public function getOne($id_annonce, $getData=false){
		$annonce=$this->db->get_where('annonce', array('id' => $id_annonce))->row();
		if($getData) $annonce=$this->getData($annonce);
		return $annonce;
	}
	public function getAllByFormation(){
		return $this->db->get("annonce")->result();
	}

	public function getAllByFormationWidthStudents($id_formation, $admin=true){

		$annonces["valid"]=$this->db->query("SELECT * from annonce where id_formation=".$id_formation." AND expiration>=CURDATE() AND expiration IS NOT NULL ORDER BY expiration" )->result();
		$annonces["expirate"]=$this->db->query("SELECT * from annonce where id_formation=".$id_formation." AND expiration<CURDATE() AND expiration IS NOT NULL AND student IS NULL ORDER BY expiration" )->result();
		$annonces['finish']=$this->db->query("SELECT * from annonce where id_formation=".$id_formation." AND student IS NOT NULL")->result();
		$annonces["autonomy"]=$this->db->query("SELECT * from annonce where id_formation=".$id_formation." AND expiration IS null")->result();
//	$annonces=$this->db->get_where("annonce", array("id_formation"=>$id_formation))->result();

if($admin){
		foreach($annonces as $type){
			foreach($type as $annonce){
				$annonce=$this->getData($annonce);
			}

		}
	}
		return $annonces;
	}
	public function getResponsesByCandidate($id_annonce, $id_student){
		$query="SELECT interested from response_annonce where id_annonce=".$id_annonce." and id_student=".$id_student;
		$result=$this->db->query($query)->row();
		if($result) return $result->interested;
		return null;
	}
	public function whiteList($id_annonce, $id_student){
		$this->db->delete("blacklist", array("id_annonce"=>$id_annonce, "id_student"=>$id_student));
		$this->db->query('INSERT INTO response_annonce ( id_annonce, id_student, interested) VALUES ('.$id_annonce.', '.$id_student.', null)');

	}
	public function createResponses( $id_annonce, $students){
		$query="INSERT INTO response_annonce ( id_annonce, id_student, interested) VALUES";
		for($i=0; $i<count($students) ; $i++){
				$query.=" (".$id_annonce.", ".$students[$i].", null)";
				if($i<count($students)-1) $query.=',';
		}
		$this->db->query($query);

	}
	public function delete($id_annonce){
		if($id_annonce==="all"){
			$this->db->empty_table("annonce");
			$this->db->empty_table("blacklist");
			$this->db->empty_table("response_annonce");
		}else{
			$this->db->delete("annonce", array("id"=>$id_annonce));
			$this->db->delete("blacklist", array("id_annonce"=>$id_annonce));
			$this->db->delete("response_annonce", array("id_annonce"=>$id_annonce));
		}

	}
	private function getData($annonce){
		$blacklist=$this->db->query("select id_student as id from blacklist where id_annonce=".$annonce->id);
		$annonce->blacklist=[];
		$annonce->response=[];
		if($annonce->expiration) $annonce->expiration=Utils::getFullDate($annonce->expiration);
		foreach ($blacklist->result() as $row){
			array_push($annonce->blacklist,$this->db->query("select id, name, firstname from student where id=".$row->id)->row());
		}
		$response=$this->db->query("select id_student as id, interested, commentaire from response_annonce where id_annonce=".$annonce->id);
		foreach ($response->result() as $row){
		$student=$this->db->query("select id, name, firstname from student where id=".$row->id)->row();
			array_push(
				$annonce->response,
				array("student"=>$student, "interested"=>$row->interested, "commentaire"=>$row->commentaire)
			);
		}
		return $annonce;
	}
}
