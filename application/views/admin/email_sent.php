<h2>Votre email a été envoyé</h2>
<p>Destinataires : <?php echo $to;?></p>
<p>Envoyeur : <?php echo $email['from'][0]." ".$email['from'][1]; ?></p>
<p>Objet : <?php echo $email['subject']; ?></p>
<p>Message : <?php echo $email['html']; ?></p>

<h2><a href="<?php echo $_SERVER['HTTP_REFERER'];?>">Retour à la formation</a></h2>