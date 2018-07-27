<?php

$subject = "ERRATUM : Entretien reporté CFA des SCIENCES";
$message_txt="Bonjour, \n
Suite à un problème interne, votre entretien au CFA des SCIENCES pour la formation ".$formation->formation." a été reporté.\n
Merci de bien vouloir vous repositionner sur un autre creneau sur ".$this->config->base_url().".\n
Pour rappel, votre mot de passe est ".$student->password."\n
Nous vous présentons nos excuses pour la gêne occasionnée.\n\n
Bien cordialement\n\n
Le secretariat du CFA des SCIENCES\n
01 44 27 71 40\n
secretariat@cfa-sciences.fr";

$message_html='<html><head></head><body><p style="margin-bottom:20px;">Bonjour,</p>
<p>Suite à un problème interne, votre entretien au CFA des SCIENCES pour la formation <a href="'.$url.'">'.$formation->formation.'</a> a été reporté.</p>
<p>Merci de bien vouloir vous repositionner sur un autre creneau sur <a href="'.$this->config->base_url().'">'.$this->config->base_url().'</a></p>
<p>Votre mot de passe pour la connexion est '.$student->password.'.</p>
<p style="margin-bottom:20px;">Nous vous présentons nos excuses pour la gêne occasionnée.</p>
<pstyle="margin-bottom:20px;">Bien cordialement</p>

<p><b>Le secretariat du CFA des SCIENCES</b></p>
<p>01 44 27 71 40</p>
<p><a href="mailto:secretariat@cfa-sciences.fr">secretariat@cfa-sciences.fr</a></p>
</body></html>';
