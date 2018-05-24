<?php 
if(isset($this->session->message) && !empty($this->session->message)){  ?> <div class="msgSent"><?php echo $this->session->message; ?></div><?php } 