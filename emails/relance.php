<?php

$subject = "Entretien CFA des SCIENCES - Relance";

$message_txt="Bonjour, \n
Vous avez postulé pour ".$formation->formation." au CFA des Sciences et votre candidature a été retenue par le jury pour un entretien de motivation.\n
À ce jour, vous ne vous êtes toujours pas positionné sur un créneau. Nous vous remercions de le faire le plus rapidement possible. Nous vous rappellons que l'entretien est obligatoire pour intégrer la formation.
Pour rappel, les entretiens se dérouleront ".$meeting["days"]." à Sorbonne Université sur le Campus Pierre et Marie Curie, 4 place Jussieu 75005 Paris. La salle vous sera précisée ultérieurement.\n
Nous vous remercions de vous positionner sur un créneau horaire en vous connectant avec votre adresse email sur ".$this->config->base_url().".\n
Votre mot de passe pour la connexion est ".$student->password.".\n\n
Bien cordialement\n\
Le secretariat du CFA des SCIENCES\n
01 44 27 71 40\n
secretariat@cfa-sciences.fr";

$message_html="<html><head></head><body><p style='margin-bottom:20px;'>Bonjour,</p>
<p>Vous avez postulé pour <a href='".$url."'>".$formation->formation."</a> au CFA des Sciences et votre candidature a été retenue par le jury pour un entretien de motivation.</p>
<p style='margin-bottom:20px;'>À ce jour, vous ne vous êtes toujours pas positionné sur un créneau. Nous vous remercions de le faire le plus rapidement possible. Nous vous rappellons que l'entretien est obligatoire pour intégrer la formation.</p>
<p >Pour rappel, les entretiens se dérouleront ".$meeting["days"]." à Sorbonne Université sur le Campus Pierre et Marie Curie, 4 place Jussieu 75005 Paris. La salle vous sera précisée ultérieurement.</p>
<p>Nous vous remercions de vous positionner sur un créneau horaire en vous connectant avec votre adresse email sur <a href='".$this->config->base_url()."'>".$this->config->base_url()."</a></p>
<p style='margin-bottom:20px;'>Votre mot de passe pour la connexion est ".$student->password.".</p>
<p style='margin-bottom:20px;'>Bien cordialement</p>
<p><b>Le secretariat du CFA des SCIENCES</b></p>
<p>01 44 27 71 40</p>
<p><a href='mailto:secretariat@cfa-sciences.fr'>secretariat@cfa-sciences.fr</a></p>
</body></html>";
