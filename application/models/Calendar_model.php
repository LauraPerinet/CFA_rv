<?php
class Calendar_model extends CI_Model{
	public function selectAllByFormation($type, $id_formation, $getParticular=true){
		$query='SELECT * FROM '.$type.'_calendar WHERE id_formation='.$id_formation;
		if(!$getParticular) $query.=' AND particular=0';
		$query.=' ORDER BY dateRV';
		return $this->db->query($query)->result();
	}
	public function getAllByStudent($type, $id_student){
		return $this->db->query('SELECT * FROM '.$type.'_calendar WHERE id_'.$type.' ='.$id_student)->result();
	}
	public function getOne($type, $id_student, $id_formation){

		return $this->db->query("SELECT * FROM ".$type."_calendar WHERE id_".$type."=".$id_student." AND id_formation=".$id_formation)->row();
	}

	public function getOneById($type, $id, $oneCol="*"){
		$col = $oneCol=="*" ? "id, id_".$type." AS id_student, location, skype" : $oneCol;
		$query="SELECT ".$col." FROM ".$type."_calendar WHERE id=".$id;
		echo $query;
		$result=$this->db->query("SELECT ".$col." FROM ".$type."_calendar WHERE id=".$id)->row();
		return $result ? $oneCol=="*" ? $result : $result->$col : $result;
	}

	public function deleteCalendar($type, $id){
		$this->db->query('DELETE FROM '.$type.'_calendar WHERE id='.$id);
	}

	public function createCalendar($type, $newDate, $id_formation, $location, $id_student=0, $skype=null){
		if(!$id_student) $id_student=0;
		$data=array(
			"dateRV"=>$newDate->format('Y-m-d H:i:sP'),
			"id_formation"=>$id_formation,
			"location"=>$location,
			"particular"=>$id_student!=0 ? true : false,
			"skype"=>$skype!==null ? $skype : 0
		);
		$this->db->insert($type."_calendar", $data);
		return $this->db->insert_id();
	}
	public function updateLocation($type, $id, $newLoc){
		$this->db->set("location", $newLoc);
		$this->db->where("id", $id);
		$this->db->update($type."_calendar");
	}
	public function updateStudent($type, $id, $id_student, $id_formation, $skype=0){
		$this->db->query("UPDATE ".$type."_calendar SET id_".$type."=0, skype=0 WHERE id_".$type."=".$id_student." AND id_formation=".$id_formation);
		$this->db->set("id_".$type, $id_student);
		$this->db->set("skype", $skype);
		$this->db->where("id", $id);
		$this->db->update($type."_calendar");
	}
	public function cancelMeeting($type, $id){
		$this->db->query("UPDATE ".$type."_calendar SET id_".$type."=0, skype=0 WHERE id=".$id);
	}
	public function countCalendarStillAvailable($type, $id_formation){
		return $this->db->query("SELECT COUNT(*) as count from ".$type."_calendar WHERE id_".$type."=0 AND id_formation=".$id_formation)->row()->count;
	}
}
