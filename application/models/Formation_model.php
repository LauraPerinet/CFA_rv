<?php
class Formation_model extends CI_Model{
	public function getAll(){
		return $this->db->query('SELECT * FROM formation')->result();
	}
	public function getOne($col, $value, $oneCol="*"){
		$result=$this->db->query('SELECT '.$oneCol.' FROM formation WHERE '.$col.'='.$value)->row();
		if($result==null) return null;
		return $oneCol=="*"? $result : $result->$oneCol;
	}
	public function createOne($ypareo, $name, $url=null){
		$data=array(
			"ypareo"=>$ypareo,
			"formation"=>$name,
			"url"=>$url
		);
		$this->db->insert('formation', $data);
		return $this->db->insert_id();
	}
	public function getAllRelationsForOneStudent($type, $id_student){
		return $this->db->query(
			'SELECT * FROM '.$type.'_formation
			INNER JOIN formation ON '.$type.'_formation.id_formation=formation.id
			WHERE id_'.$type.'='.$id_student
		)->result();
	}

	public function countAllRelations($type, $id_formation=null){
		$this->db->select("*");
		$this->db->from($type.'_formation');
		if($id_formation) $this->db->where('id_formation', $id_formation);
		return $this->db->count_all_results();
	}

	public function countStudentsStatus($type, $id_status, $id_status2=null, $id_formation=null){
		if($id_status2){
			$query="SELECT COUNT(*) FROM ".$type."_formation WHERE ( id_status=".$id_status." OR id_status=".$id_status2.")";
		}else{
			$query="SELECT COUNT(*) FROM ".$type."_formation WHERE id_status=".$id_status;
		}

		if($id_formation)$query.=" AND id_formation=".$id_formation;
		return $this->db->query($query)->row_array()["COUNT(*)"];
	}

	public function getOneRelation($type, $id_student, $id_formation){
		return $this->db->query('SELECT * FROM '.$type.'_formation WHERE id_'.$type.'='.$id_student.' AND id_formation='.$id_formation)->row();
	}

	public function createNewRelation($type, $id_student, $id_formation, $id_status){
		$data=array(
				'id_'.$type=>$id_student,
				'id_formation'=>$id_formation,
				'id_status'=> empty($id_status) ? ($type=="candidate" ? 0 : 10) : 1,
				'lastModif'=>date("Y-m-d")
		);
		$this->db->insert($type.'_formation', $data);
	}

	public function updateStatus($type, $id_student, $id_formation, $new_status){
		$query="UPDATE ".$type."_formation SET id_status=".$new_status.", relance=0,lastModif='".date("Y-m-d")."' WHERE id_".$type."=".$id_student." AND id_formation=".$id_formation;
		$this->db->query($query);
	}
	public function updateOldStatus($type,$id_formation, $old_status ){
		$this->db->query('UPDATE '.$type.'_formation SET id_status='.($old_status+1).', lastModif="'.date("Y-m-d").'" WHERE id_status='.$old_status.' AND id_formation='.$id_formation);
	}
	public function increaseRelance($type, $id_student, $id_formation, $relance){
		$query='UPDATE '.$type.'_formation SET relance='.($relance+1).', lastModif="'.date("Y-m-d").'" WHERE id_'.$type.'='.$id_student.' AND id_formation='.$id_formation;

		$this->db->query($query);
	}
	public function updateFormation($id_formation, $name, $url=null){
		$this->db->query('UPDATE formation SET formation="'.$name.'", url="'.$url.'" WHERE id='.$id_formation);
		//$this->db->set("formation", $name)->where("id", $id_formation)->update("formation");
	}
	public function testDeleteFormation($id_formation){
		$data=array(
			"student_calendar"=>$this->db->where('id_formation',$id_formation)->from("student_calendar")->count_all_results(),
			"candidate_calendar"=>$this->db->where('id_formation',$id_formation)->where("id_candidate !=", 0)->from("candidate_calendar")->count_all_results(),
			"student_formation"=>$this->db->where('id_formation',$id_formation)->from("student_formation")->count_all_results(),
			"candidate_formation"=>$this->db->where('id_formation',$id_formation)->from("candidate_formation")->count_all_results()
		);
		$data['total']=$data["student_calendar"]+$data["student_formation"]+$data["candidate_calendar"]+$data["candidate_formation"];

		return $data;
	}
	public function deleteFormation($id_formation){
		$candidate=$this->db->query("SELECT id_candidate FROM candidate_formation WHERE id_candidate!=0 AND id_formation=".$id_formation)->result();
		$students=$this->db->query("SELECT id_student FROM student_formation WHERE id_student!=0 AND id_formation=".$id_formation)->result();


		$this->db->query('DELETE FROM student_calendar WHERE id_formation='.$id_formation);
		$this->db->query('DELETE FROM candidate_calendar WHERE id_formation='.$id_formation);
		$this->db->query('DELETE FROM student_formation WHERE id_formation='.$id_formation);
		$this->db->query('DELETE FROM candidate_formation WHERE id_formation='.$id_formation);
		$this->db->query('DELETE FROM admin_formation WHERE id_formation='.$id_formation);
		$this->db->query('DELETE FROM staffpart_formation WHERE id_formation='.$id_formation);
		$this->db->query('DELETE FROM formation WHERE id='.$id_formation);


		foreach($candidate as $student){
			if(count($this->getAllRelationsForOneStudent("candidate", $student->id_candidate))==0){
				$this->db->query('DELETE FROM candidate WHERE id='.$student->id_candidate);
			}
		}
		foreach($students as $student){
			if(count($this->getAllRelationsForOneStudent("student", $student->id_student))==0){
				$this->db->query('DELETE FROM student WHERE id='.$student->id_student);
			}
		}
	}
	public function getStatus($type=null){
		$query='SELECT * FROM status';
		if($type) $query.=' WHERE type="'.$type.'"';
		return $this->db->query($query)->result();
	}
	/*
	public function getStaffs($id_formation){
		$ref=$this->db->query("SELECT * FROM admin, admin_formation where id_formation=".$id_formation." AND admin.id=admin_formation.id_admin")->result();
		if(count($ref)==0) $ref=$this->getDefaultAddress();

		return $ref;
	}
	public function getDefaultAddress(){
		$ref=$this->db->query("SELECT * FROM admin where DefaultRef=1")->result();
		return $ref;
	}

	public function deleteReferend($id_formation, $type){

		$this->db->query("DELETE FROM ".$type."_formation WHERE id_formation=".$id_formation);
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
*/



}
