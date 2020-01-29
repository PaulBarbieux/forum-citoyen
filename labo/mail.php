<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<TITLE>Wwoof labo</TITLE>
</HEAD>

<BODY>
<H1>Send trusted mail</H1>
<FORM method="post">
<LABEL>From email<INPUT type="email" name="from" value="<?= EMAIL_SEND ?>"></LABEL><BR>
<LABEL>From name <INPUT type="text" name="fromname" value="<?= SITE_TITLE ?>"></LABEL><BR>
<LABEL>Reply to email<INPUT type="email" name="replyto"></LABEL><BR><BR>
<LABEL>To email<INPUT type="email" name="to"></LABEL><BR><BR>
<LABEL>Texte</LABEL><BR>
<TEXTAREA cols="100" rows="5" name="body"></TEXTAREA><BR><BR>
<INPUT type="submit" name="send">
</FORM>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_POST['send'])) {
	require_once $_SERVER['DOCUMENT_ROOT'].'/includes/phpmailer/PHPMailerAutoload.php';
	$mail = new PHPMailer;
	$mail->From = $_POST['from'];
	$mail->FromName = $_POST['fromname'];
	$mail->addReplyTo($_POST['replyto']);
	$mail->addAddress($_POST['to']);
	$mail->Subject = "[TEST] ".SITE_TITLE;
	$mail->isHTML(true);
	$mail->Body = $_POST['body']; 
	if(!$mail->send()) {
		print "<P>Error from PHPMailer : ".$mail->ErrorInfo."</P>";
	} else {
		print "<P>Mail envoyé à ".$_POST['to'];
	}
}
?>
</body>
</HTML>
