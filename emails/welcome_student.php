<?php

$subject="Des annonces sont en ligne";

$message_txt="Bonjour, des offres d'emploi sont en ligne sur ".$this->config->base_url().". Votre mot de passe pour la connexion est ".$student->password.".\n\n Bien cordialement\n\
Le secretariat du CFA des SCIENCES\n
01 44 27 71 40\n
secretariat@cfa-sciences.fr";

$message_html="<html><head></head><body><p style='margin-bottom:20px;'>Bonjour,</p>
<p style='margin-bottom:20px;'>Des offres d'emploi pour votre apprentissage sont en ligne sur ".$this->config->base_url().".</p>
<p style='margin-bottom:20px;'>Votre mot de passe pour la connexion est ".$student->password.".</p>
<p style='margin-bottom:20px;'>Bien cordialement</p>

<p><b>Le secretariat du CFA des SCIENCES</b></p>
<p>01 44 27 71 40</p>
<p><a href='mailto:secretariat@cfa-sciences.fr'>secretariat@cfa-sciences.fr</a></p>
</body></html>";
