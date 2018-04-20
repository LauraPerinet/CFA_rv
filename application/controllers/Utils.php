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

	
}