<?php 
$date=explode(" ", $meeting->dateRV);
$day=Utils::getFullDate($date[0]);
$subject="Confirmation de votre entretien au CFA des SCIENCES";
if($meeting->location===""){
	$loc=". La salle vous sera précisée ultérieurement.";
}else{
	$loc=" en salle ".$meeting->location".";
}


$message_txt="Bonjour,\n Nous vous confirmons votre entretien le ".$day." à ".$date[1]." pour votre entretien de motivation pour la formation->formation ".$formation->formation.". \n 
Merci de vous présenter le jour dit à Sorbonne Université sur le Campus Pierre et Marie Curie, 4 place Jussieu 75005 Paris  en salle ".$meeting->location".\n Bien cordialement \n L'équipe du CFA des SCIENCES.";

$message_html="<html><head></head><body><p>Bonjour,</p>
<p>Nous vous confirmons votre entretien le ".$day." à ".$date[1]." pour votre entretien de motivation pour la formation->formation ".$formation->formation.". </p>
<p>Merci de vous présenter le jour dit à Sorbonne Université sur le Campus Pierre et Marie Curie, 4 place Jussieu 75005 Paris en salle ".$meeting->location".</p>
<p>Bien cordialement</p>
<p>L'équipe du CFA des SCIENCES</p>
</body></html>";