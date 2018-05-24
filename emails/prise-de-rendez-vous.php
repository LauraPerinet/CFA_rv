<?php

$subject = "Entretien CFA des SCIENCES";
				
$message_txt="Bonjour, \n
Vous avez postulé pour ".$formation->formation." au CFA des Sciences et nous sommes heureux de vous informer que votre candidature a été retenue par le jury pour un entretien de motivation.\n
Les entretiens se dérouleront les ".$meeting["days"]." à Sorbonne Université sur le Campus Pierre et Marie Curie, 4 place Jussieu 75005 Paris. La salle vous sera précisée ultérieurement.\n
Nous vous remercions de vous positionner sur un créneau horaire en vous connectant avec votre adresse email sur ".$this->config->base_url().".\n
Votre mot de passe pour la connexion est ".$student->password.".\n\n
Bien cordialement\n\
L'équipe du CFA des SCIENCES";

$message_html="<html><head></head><body><p>Bonjour,</p>
<p>Vous avez postulé pour ".$formation->formation." au CFA des Sciences et nous sommes heureux de vous informer que votre candidature a été retenue par le jury pour un entretien de motivation.</p>
<p>Les entretiens se dérouleront les ".$meeting["days"]." à Sorbonne Université sur le Campus Pierre et Marie Curie, 4 place Jussieu 75005 Paris. La salle vous sera précisée ultérieurement.</p>
<p>Nous vous remercions de vous positionner sur un créneau horaire en vous connectant avec votre adresse email sur <a href='".$this->config->base_url()."'>".$this->config->base_url()."</a></p>
<p>Votre mot de passe pour la connexion est ".$student->password.".</p>
<p>Bien cordialement</p>
<p>L'équipe du CFA des SCIENCES</p>
</body></html>";