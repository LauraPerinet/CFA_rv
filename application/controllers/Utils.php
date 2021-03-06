<?php
class Utils{

	public static function getFullDate($day){
		$frenchDays=array( "Dimanche","Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
		$frenchMonth=array("janvier","fevrier","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","decembre");
		$d=new DateTime($day);
		$frDay=$frenchDays[$d->format('w')];
		$frMonth=$frenchMonth[$d->format('n')-1];
		return $frDay." ".$d->format('d')." ".$frMonth." ".$d->format('Y');;
	}
	public static function getFrenchFormat($day){
		$d=new DateTime($day);
		return $d->format('d/m/Y');
	}
	public static function getDateMax($day){
		$date=new DateTime($day);
		$subDays=3;
		if($date->format("w")<2) $subDays=5;
		$date->sub(new DateInterval('P'.$subDays.'D'));
		return($date);
	}
	public static function canStillChange($day){
		$maxDate=Utils::getDateMax($day);
		$today=new DateTime();
		return $maxDate->diff($today)->invert;
	}
}