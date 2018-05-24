<?php 
$date=explode(" ", $meeting->dateRV);
$hour=explode(":", $date[1]);
$day=Utils::getFullDate($date[0]);
$subject="Confirmation de votre entretien au CFA des SCIENCES";
	if($meeting->skype==0){
		if($meeting->location===""){
			$loc="Merci de vous présenter le jour dit à Sorbonne Université sur le Campus Pierre et Marie Curie, 4 place Jussieu 75005 Paris. La salle vous sera précisée ultérieurement.";
		}else{
			$loc="Merci de vous présenter le jour dit à Sorbonne Université sur le Campus Pierre et Marie Curie, 4 place Jussieu 75005 Paris en salle ".$meeting->location.".";
		}
	}else{
		$loc="L'entretien se déroulera sur Skype";
	}


$message_txt="Bonjour,\n Nous vous confirmons votre entretien le ".$day." à  ".$hour[0]."h".$hour[1]." pour votre entretien de motivation pour la formation ".$formation->formation.". \n 
".$loc."\n Bien cordialement \n L'équipe du CFA des SCIENCES.";

$message_html="<html><head></head><body><p>Bonjour,</p>
<p>Nous vous confirmons votre entretien le ".$day." à  ".$hour[0]."h".$hour[1]." pour votre entretien de motivation pour la formation ".$formation->formation.". </p>
<p>".$loc."</p>
<p>Bien cordialement</p>
<p>L'équipe du CFA des SCIENCES</p>
</body></html>";