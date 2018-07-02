<?php 

$subject="Annulation de votre entretien au CFA des SCIENCES";


$message_txt="Bonjour,\n Votre entretien au CFA des SCIENCES pour la formation ".$formation->formation." a été annulé à votre demande. \n\n Nous vous souhaitons une bonne continuation.\n Bien cordialement \n Le secretariat du CFA des SCIENCES\n
01 44 27 71 40\n
secretariat@cfa-sciences.fr";

$message_html="<html><head></head><body><p style='margin-bottom:20px;'>Bonjour,</p>
<p style='margin-bottom:20px;'>Votre entretien de motivation pour la formation ".$formation->formation." a été annulé à votre demande. </p>
<p style='margin-bottom:20px;'>Nous vous souhaitons une bonne continuation</p>
<p style='margin-bottom:20px;'>Bien cordialement</p>

<p><b>Le secretariat du CFA des SCIENCES</b></p>
<p>01 44 27 71 40</p>
<p><a href='mailto:secretariat@cfa-sciences.fr'>secretariat@cfa-sciences.fr</a></p>
</body></html>";