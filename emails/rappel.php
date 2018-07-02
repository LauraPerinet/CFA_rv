<?php 
$date=explode(" ", $meeting->dateRV);
$hour=explode(":", $date[1]);
$day=Utils::getFullDate($date[0]);
$subject="Rappel : entretien au CFA des SCIENCES";
	if($meeting->skype==0){
		if($meeting->location===""){
			$loc="Bâtiment Esclangon - Bureau 210";
		}else{
			$loc=$meeting->location;
		}
	}else{
		$loc="L'entretien se déroulera sur Skype";
	}
$pw="";
if($formation->ypareo=="LICENCE PRO PROJET WEB SORBONNE"){
	$pw="<p>Lors de l'entretien, il vous sera demandé de présenter votre parcours. </p>
<p>Si des travaux réalisés lors de vos formations ou des projets personnels peuvent soutenir votre candidature, munissez-vous des documents ou des urls pour en présenter quelques éléments.</p>";

}

$message_txt="Bonjour,\n N'oubliez pas votre entretien de motivation pour la formation ".$formation->formation." :\n\n 
".$day." à  ".$hour[0]."h".$hour[1]." \n
Sorbonne Université - Campus Pierre et Marie Curie\n
4 place Jussieu 75005 Paris\n 
Métro Jussieu (lignes 7 et 10)\n
".$loc."\n
".$pw."\n 
Bien cordialement \n 
Le secretariat du CFA des SCIENCES\n
01 44 27 71 40\n
secretariat@cfa-sciences.fr";

$message_html="<html><head></head><body><p style='margin-bottom:20px;'>Bonjour,</p>
<p style='margin-bottom:20px;'> N'oubliez pas votre entretien de motivation pour la formation  ".$formation->formation." :</p>

<p style='text-align:center;'>".$day." à  ".$hour[0]."h".$hour[1]."</p>
<p style='text-align:center;'>Sorbonne Université - Campus Pierre et Marie Curie</p>
<p style='text-align:center;'>4 place Jussieu 75005 Paris</p> 
<p style='text-align:center;'>Métro Jussieu (lignes 7 et 10)</p>
<p style='text-align:center; margin-bottom:20px;'>".$loc."</p>
".$pw."
<p style='margin-bottom:20px;'>Nous vous souhaitons un bon entretien.</p>

<p><b>Le secretariat du CFA des SCIENCES</b></p>
<p>01 44 27 71 40</p>
<p><a href='mailto:secretariat@cfa-sciences.fr'>secretariat@cfa-sciences.fr</a></p>
</body></html>";