<?php 

$subject="Entretien ".$formation->ypareo."| ANNULATION";

$message_txt=$formation->ypareo."\n\n".$student->name." ".$student->firstname."\n\n".$student->email."\n\n".$student->phone."\n\n";

$message_html="<html><head></head><body>
	<p><b>ANNULATION ENTRETIEN</b></p>
	<p><b>Etudiant : </b><a href='".$this->config->base_url()."index.php/student/casParticulier/candidate/".$student->id."'>".$student->name." ".$student->firstname."</a></p>
	<p><b>Email : ".$student->email."</b></p>
	<p><b>Phone : ".$student->phone."</b></p>
	<p><b>Annulé par </b>".$this->session->user->firstname." ".$this->session->user->name."</p>
</body></html>";