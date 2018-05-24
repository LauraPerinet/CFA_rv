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
		if(!empty($search["formation"] || $search["year"]|| $search["type"])){ 
			$query=$this->createQuery(  $search["formation"], $search["year"], $search["type"]);
		}
		$query.=" ORDER BY name";
		return $query=$this->db->query($query)->result();
	}
	
	public function getStudentFormations($type, $id_student, $id_status=null, $id_status2=null){
		$query="SELECT id_formation, id_status, ypareo, status
			FROM formation, ".$type."_formation, status 
			WHERE ".$type."_formation.id_".$type."=".$id_student." AND ".$type."_formation.id_status=status.id AND ".$type."_formation.id_formation=formation.id";
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
			AND id_formation=".$id_formation." WHERE id =".$id_student)->row();
	}
	
	public function getAllStudentsByFormation($type, $id_formation, $id_status){
		$query='SELECT '.$type.'.id, name, firstname, email, phone, date_candidature, id_status, status 
		FROM '.$type.', '.$type.'_formation, status 
		WHERE id_'.$type.'='.$type.'.id 
		AND status.id=id_status AND id_formation='.$id_formation;
		
		if($id_status!==null){
			$query .= " AND id_status=".$id_status ;
		}
		return $this->db->query($query )->result();
	}
	
	public function getMinYear($type){
		$minYear= $this->db->query('SELECT MIN(date_candidature) AS minYear FROM '.$type)->row()->minYear;
		return $minYear==0 ? 2018:$minYear;
	}
	
	private function createQuery( $formation=null, $year=null, $type="candidate"){
		$query='SELECT * FROM '.$type;
		if(!empty($formation | $year | $type)) $query.=' where ';
		if($formation){
			$query.='id IN ( SELECT id_'.$type.' FROM '.$type.'_formation WHERE id_formation='.$formation.')';
			if($year) $query.=' and';
		}
		if($year ) $query.=" date_candidature=".$year;
		return $query;
	}
}