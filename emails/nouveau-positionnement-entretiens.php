<?php
$date=explode(" ", $meeting->dateRV);
$day=Utils::getFullDate($date[0]);
$subject="Entretien ".$formation->ypareo."| Nouveau positionnement";
if($meeting->distant!="") $meeting->location=$meeting->distant;
$alert="";
if($stillAvailable<=3) $alert="Attention : plus que ".$stillAvailable." sessions de d'entretien libres pour ".$formation->ypareo;
$message_txt=$alert."\n\n".$formation->ypareo."\n\n".$day." à ".$date[1]."\n\n".$student->name." ".$student->firstname."\n\n".$student->email."\n\n".$student->phone."\n\n";

$message_html="<html><head></head><body>
	<p style='color:red;'>".$alert."</p>
	<p><b>Date : </b>".$day." à ".$date[1]."</p>
	<p><b>Salle : </b>".$meeting->location."</p>
	<p><b>Etudiant : </b><a href='".$this->config->base_url()."index.php/student/casParticulier/candidate/".$student->id."'>".$student->name." ".$student->firstname."</a></p>
	<p><b>Courriel : ".$student->email."</b></p>
	<p><b>Téléphone : ".$student->phone."</b></p>
</body></html>";
