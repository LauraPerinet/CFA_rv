<?php

$subject = "ERRATUM : Entretien reporté CFA des SCIENCES";
				
$message_txt="Bonjour, \n
Suite à un problème interne, votre entretien au CFA des SCIENCES pour la formation ".$formation->formation." a été reporté.\n 
Merci de bien vouloir vous repositionner sur un autre creneau sur ".$this->config->base_url().".\n
Pour rappel, votre mot de passe est ".$student->password."\n
Nous vous présentons nos excuses pour la gêne occasionnée.\n\n
Bien cordialement\n\n
L'équipe du CFA des SCIENCES";

$message_html="<html><head></head><body><p>Bonjour,</p>
<p>Suite à un problème interne, votre entretien au CFA des SCIENCES pour la formation ".$formation->formation." a été reporté.</p>
<p>Merci de bien vouloir vous repositionner sur un autre creneau sur <a href='".$this->config->base_url()."'>".$this->config->base_url()."</a></p>
<p>Votre mot de passe pour la connexion est ".$student->password.".</p>
<p>Nous vous présentons nos excuses pour la gêne occasionnée.</p>
<p>Bien cordialement</p>
<p>L'équipe du CFA des SCIENCES</p>
</body></html>";