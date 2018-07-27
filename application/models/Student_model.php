<?php
class Student_model extends CI_Model{
	public function deleteStudent($type, $id_student){
		$this->db->query('DELETE FROM '.$type.' WHERE id='.$id_student);
		$this->db->query('DELETE FROM '.$type.'_formation WHERE id_'.$type.'='.$id_student);
		$this->db->query('UPDATE '.$type.'_calendar SET id_'.$type.'=0 WHERE id_'.$type.'='.$id_student);
	}
	public function createNewStudent($type, $data){
		$this->db->insert( $type , $data);
		return $this->db->insert_id();
	}
	public function updateStudent($type, $id, $email, $phone){
		$this->db->set("email", $email)->set("phone", $phone)->where("id", $id)->update($type);
	}
	public function countStudent($type){
		return $this->db->count_all_results($type);
	}
	public function getOne($type, $col, $value, $oneCol="*"){
		$result=$this->db->query('SELECT '.$oneCol.'
		FROM '.$type.'
		where '.$col.'='.$value )->row();
		return $result ? $oneCol=="*" ? $result : $result->$oneCol : $result;
	}

	public function selectStudentsBy($search){
		$query='SELECT * FROM '.$search["type"];
		if(!empty($search["formation"] || $search["type"])){
			$query=$this->createQuery(  $search["formation"], $search["type"]);
		}
		$query.=" ORDER BY name";
		return $query=$this->db->query($query)->result();
	}

	public function getStudentFormations($type, $id_student, $id_status=null, $id_status2=null, $between=null, $relance=0){
		$query="SELECT id_formation, id_status, ypareo, status, lastModif, relance
			FROM formation, ".$type."_formation, status
			WHERE ".$type."_formation.id_".$type."=".$id_student." AND ".$type."_formation.id_status=status.id AND ".$type."_formation.id_formation=formation.id";
		if(!empty($relance) && $relance!=0) $query.=" AND relance >0";
		if($between["from"]!==null) $query.=" AND lastModif BETWEEN '".$between["from"]."' AND '".$between["to"]."'";

		if($id_status!==null || $id_status==="0" ){
			if($id_status2!==null){
				$query.=" AND (".$type."_formation.id_status=".$id_status." OR ".$type."_formation.id_status=".$id_status2.")";
			}else{
				$query.=" AND ".$type."_formation.id_status=".$id_status;
			}
		}
		return $this->db->query($query)->result();
	}

	public function getStudentByRelation($type, $id_student, $id_formation){
		return $this->db->query(
			"SELECT * FROM ".$type."
			INNER JOIN ".$type."_formation
			ON id_".$type."=".$type.".id
			AND id_formation=".$id_formation." WHERE id =".$id_student." ORDER BY name")->row();
	}

	public function getAllStudentsByFormation($type, $id_formation, $id_status, $between=null, $relance=0){
		$query='SELECT '.$type.'.id, name, firstname, email, phone, date_candidature, id_status, status, lastModif, relance
		FROM '.$type.', '.$type.'_formation, status
		WHERE id_'.$type.'='.$type.'.id
		AND status.id=id_status AND id_formation='.$id_formation;
		if(!empty($relance) && $relance!=0) $query.=" AND relance >0";
		if($between["from"]!==null) $query.=" AND lastModif BETWEEN '".$between["from"]."' AND '".$between["to"]."'";

		if($id_status!==null){
			$query .= " AND id_status=".$id_status ;
		}
		$query.=" ORDER BY name";
		return $this->db->query($query )->result();
	}

	public function getAllIdByFormation($type, $id_formation, $id_status){
		$res=[];
		$ids=$this->db->query("SELECT id_".$type." from ".$type."_formation WHERE id_formation=".$id_formation." AND id_status=".$id_status)->result();
		foreach($ids as $id){
			array_push($res, $id->id_student);
		}
		return $res;
	}
	public function getOlderModif($type){
		$lastModif= $this->db->query('SELECT MIN(lastModif) AS lastModif FROM '.$type.'_formation')->row()->lastModif;
		return $lastModif==0 ? date("d/m/Y"):$lastModif;
	}

	private function createQuery( $formation=null, $type="candidate"){
		$query='SELECT * FROM '.$type;
		if(!empty($formation | $type)) $query.=' where ';
		if($formation){
			$query.='id IN ( SELECT id_'.$type.' FROM '.$type.'_formation WHERE id_formation='.$formation.')';
		}
		return $query;
	}

	public function deleteStaffs( $id, $type="admin" ){
		$this->db->query("DELETE FROM ".$type."_formation WHERE id_".$type."=".$id);
		$this->db->query("DELETE FROM ".$type." WHERE id=".$id);
	}

	public function getStaffsByFormation($type, $id_formation){
		$ref=$this->db->query("SELECT * FROM ".$type.", ".$type."_formation where id_formation=".$id_formation." AND ".$type.".id=".$type."_formation.id_".$type)->result();
		if($type==="admin" && count($ref)==0) $ref=$this->getDefaultAddress();

		return $ref;
	}
	public function getDefaultAddress(){
		$ref=$this->db->query("SELECT * FROM admin where DefaultRef=1")->result();
		return $ref;
	}

	public function deleteReferend($id_formation, $type){
		$this->db->query("DELETE FROM ".$type."_formation WHERE id_formation=".$id_formation);
	}

	public function deleteReferendByStaff($id_admin, $type){
		$this->db->query("DELETE FROM ".$type."_formation WHERE id_".$type."=".$id_admin);
	}
	public function updateStaff($id_admin, $type, $data){
		$this->db->set($data);
		$this->db->where('id', $id_admin);
		$this->db->update($type);
	}
	public function getOneStaff($type, $id_admin){
		$admin=$this->db->query("SELECT * FROM ".$type." where id=".$id_admin)->row();
		$admin->formations=$this->db->query("SELECT id_formation, ypareo FROM ".$type."_formation, formation WHERE id_".$type."=".$admin->id." AND id_formation=formation.id")->result();
		return $admin;
	}

	public function addReferend($admin, $id_formation, $type="admin"){
		$data=array(
			"id_".$type=>$admin,
			"id_formation"=>$id_formation
		);
		$this->db->insert($type."_formation", $data);
	}
	public function getStaffs($type="admin", $id_formation=null){
		$admins=$this->db->query("SELECT * FROM ".$type)->result();

		foreach($admins as $admin){
				if($id_formation){
					$admin->isRef=$this->db->query("SELECT * FROM ".$type."_formation WHERE id_".$type."=".$admin->id." AND id_formation=".$id_formation)->row();
				}else{
					$admin->formations=$this->db->query("SELECT id_formation, ypareo FROM ".$type."_formation, formation WHERE id_".$type."=".$admin->id." AND id_formation=formation.id")->result();
				}
		}

		return $admins;
	}
	public function getStudentByAnnonce($id_annonce){
		$query="SELECT DISTINCT student.id, `name`, `firstname`, `email`, `formation`, `password` from student, formation, student_formation, annonce where
		annonce.id=".$id_annonce." AND
		annonce.id_formation=formation.id AND
		annonce.id_formation=student_formation.id_formation AND
		 student.id=student_formation.id_student AND
		  student.id NOT IN (select id_student from blacklist where id_annonce=annonce.id)";
		return $this->db->query($query)->result();
	}

}
