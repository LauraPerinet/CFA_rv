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
	public function createOne($ypareo, $name){
		$data=array(
			"ypareo"=>$ypareo,
			"formation"=>$name
		);
		$this->db->insert('formation', $data);
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
				'id_status'=> empty($id_status) ? ($type=="candidate" ? 0 : 10) : 1
		);
		$this->db->insert($type.'_formation', $data);
	}
	
	public function updateStatus($type, $id_student, $id_formation, $new_status){
		$this->db->query("UPDATE ".$type."_formation SET id_status=".$new_status." WHERE id_".$type."=".$id_student." AND id_formation=".$id_formation);
	}
	public function updateOldStatus($type,$id_formation, $old_status ){
		$this->db->query('UPDATE '.$type.'_formation SET id_status='.($old_status+1).' WHERE id_status='.$old_status.' AND id_formation='.$id_formation);
	}
	
	public function getStatus($type=null){
		$query='SELECT * FROM status';
		if($type) $query.=' WHERE type="'.$type.'"';
		return $this->db->query($query)->result();
	}
	public function getReferends($id_formation){
		return $this->db->query("SELECT * FROM admin, admin_formation where id_formation=".$id_formation)->result();
	}
	public function deleteReferend($id_formation){
		$this->db->query("DELETE FROM admin_formation WHERE id_formation=".$id_formation);
	}
	public function addReferend($admin, $id_formation){
		$data=array(
					"id_admin"=>$admin,
					"id_formation"=>$id_formation
				);
				$this->db->insert("admin_formation", $data);
	}
	public function getAdmins($id_formation=null){
		$admins=$this->db->query("SELECT * FROM admin")->result();
		if($id_formation){
			foreach($admins as $admin){
				$admin->isRef=$this->db->query("SELECT * FROM admin_formation WHERE id_admin=".$admin->id." AND id_formation=".$id_formation)->row();
			}
		}
		return $admins;
	}

	
	
}