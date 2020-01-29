<?php
require_once ROOT.'/includes/phpmailer/PHPMailerAutoload.php';
$mail = new PHPMailer;
$mail->From = SEND_EMAIL;
$mail->FromName = SITE_TITLE;
$mail->isHTML(true);
?>