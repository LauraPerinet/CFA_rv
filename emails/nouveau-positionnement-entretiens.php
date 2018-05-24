<?php 
$date=explode(" ", $meeting->dateRV);
$day=Utils::getFullDate($date[0]);
$subject="Entretien ".$formation->ypareo."| Nouveau positionnement";
if($meeting->skype!=0) $meeting->location="skype";
$message_txt=$formation->ypareo."\n\n".$day." à ".$date[1]."\n\n".$student->name." ".$student->firstname."\n\n".$student->email."\n\n".$student->phone."\n\n";

$message_html="<html><head></head><body>
	<p><b>Date :</b>".$day." à ".$date[1]."</p>
	<p><b>Salle :</b>".$meeting->location."</p>
	<p><b>Etudiant : </b><a href='".$this->config->base_url()."index.php/student/casParticulier/candidate/".$student->id."'>".$student->name." ".$student->firstname."</a></p>
	<p><b>Email : ".$student->email."</b></p>
	<p><b>Phone : ".$student->phone."</b></p>
</body></html>";