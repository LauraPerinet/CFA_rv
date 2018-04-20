<?php 
$date=explode(" ", $meeting->dateRV);
$day=Utils::getFullDate($date[0]);
$subject="Confirmation de votre entretien au CFA des SCIENCES";

$message_txt="Bonjour,\n Nous vous confirmons votre entretien le ".$day." à ".$date[1]." pour votre entretien de motivation pour la formation->formation ".$formation->formation.". \n 
Merci de vous présenter le jour dit à Sorbonne Université sur le Campus Pierre et Marie Curie, 4 place Jussieu 75005 Paris. La salle vous sera précisée ultérieurement.\n Bien cordialement";

$message_html="<html><head></head><body><p>Bonjour,</p>
<p>Nous vous confirmons votre entretien le ".$day." à ".$date[1]." pour votre entretien de motivation pour la formation->formation ".$formation->formation.". </p>
<p>Merci de vous présenter le jour dit à Sorbonne Université sur le Campus Pierre et Marie Curie, 4 place Jussieu 75005 Paris. La salle vous sera précisée ultérieurement</p>
<p>Bien cordialement</p>
</body></html>";