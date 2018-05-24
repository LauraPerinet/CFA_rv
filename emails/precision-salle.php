<?php 
$date=explode(" ", $meeting->dateRV);
$hour=explode(":", $date[1]);
$day=Utils::getFullDate($date[0]);
$subject="Votre entretien au CFA des SCIENCES";

if($meeting->skype==0){
	$loc="Merci de vous présenter le jour dit à Sorbonne Université sur le Campus Pierre et Marie Curie, 4 place Jussieu 75005 Paris  en salle ".$meeting->location.".\n";
}else{
	$loc="L'entretien se fera par skype.";
}

$message_txt="Bonjour,\n Nous vous confirmons votre entretien le ".$day." à ".$hour[0]."h".$hour[1]." pour votre entretien de motivation pour la formation ".$formation->formation.". \n ".$loc." Bien cordialement \n L'équipe du CFA des SCIENCES.";

$message_html="<html><head></head><body><p>Bonjour,</p>
<p>Nous vous confirmons votre entretien le ".$day." à  ".$hour[0]."h".$hour[1]." pour votre entretien de motivation pour la formation ".$formation->formation.". </p>
<p>".$loc."</p>
<p>Bien cordialement</p>
<p>L'équipe du CFA des SCIENCES</p>
</body></html>";