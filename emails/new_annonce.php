<?php
$date=explode(" ", $annonce->expiration);
$day=Utils::getFullDate($date[0]);
$subject="Nouvelle offre d'apprentissage sur CFA des SCIENCES";


$message_txt="Bonjour,\n Une nouvelle offre d'apprentissage à été publiée sur <a href='".$this->config->base_url()."'>".$this->config->base_url()."</a>.\n
 ".$annonce->title." \n
 Elle sera disponible jusqu'au ".$day."
Merci de nous indiquer si vous êtes interessé(e) par cette offre. Nous nous occupons de transferer les CVs.\n

Bien cordialement \n Le secretariat du CFA des SCIENCES\n
01 44 27 71 40\n
secretariat@cfa-sciences.fr";

$message_html="<html><head></head><body><p style='margin-bottom:20px;'>Bonjour,</p>
<p style='margin-bottom:20px;'>Une nouvelle offre d'apprentissage à été publiée sur <a href='".$this->config->base_url()."'>".$this->config->base_url()."</a> : </p>
<p style='text-align:center'><b>".$annonce->title."</b></p>
<p>Elle sera disponible jusqu'au <b>".$day."</b></p>
<p style='margin-bottom:20px;> Merci de nous indiquer si vous êtes interessé(e) par cette offre. Nous nous occupons de transferer les CVs. </p>
<p style='margin-bottom:20px;'>Votre mot de passe pour la connexion est ".$student->password.".</p>
<p style='margin-bottom:20px;'>Bien cordialement</p>

<p><b>Le secretariat du CFA des SCIENCES</b></p>
<p>01 44 27 71 40</p>
<p><a href='mailto:secretariat@cfa-sciences.fr'>secretariat@cfa-sciences.fr</a></p>
</body></html>";
