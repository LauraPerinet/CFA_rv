<?php 

$subject= $type="candidate" ? "Candidat A rappeler : Problème entretien de selection" : "Problème présence soutenance";

$message_txt="Formation : ".$formation->ypareo." \n\n
	Etudiant : ".$student->name." ".$student->firstname." \n\n
	Email : ".$student->email." \n\n
	Phone : ".$student->phone." \n\n
	Message: \n\n".$message;

$message_html="<html><head></head><body>
	<p><b>Formation : </b>".$formation->ypareo."</p>
	<p><b>Etudiant : </b><a href='".$this->config->base_url()."index.php/student/casParticulier/".$student->id."'>".$student->name." ".$student->firstname."</a></p>
	<p><b>Email : ".$student->email."</b></p>
	<p><b>Phone : ".$student->phone."</b></p>
	<p></p>
	<p>Message:</p>
	<p>".$message."</p>
</body></html>";