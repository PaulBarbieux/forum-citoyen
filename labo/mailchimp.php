<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<TITLE>Wwoof labo</TITLE>
</HEAD>

<BODY>
<H1>Subscribe to MailCimp</H1>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$listId = "732a7ffb12";
$email = $_GET['email'];
if ($email == "") {
	print "Give email";
	exit;
}
require $_SERVER['DOCUMENT_ROOT']."/_mailchimp/src/Mailchimp.php";
$mailchimp = new Mailchimp("5c6d6fdabe7e3da8859c939746548c41-us3");
$mailchimp_lists = new Mailchimp_Lists($mailchimp);
try {
	$subscriber = $mailchimp_lists->subscribe($listId, array('email'=>$email), array('FNAME'=>'Paul', 'LNAME'=>'Barbieux'), "html", false);
} catch (Exception $e) {
	$body = $e->getMessage();
}
if (empty($e)) {
	if ($mailchimp_lists->errorCode) {
		$body = "Error !<br>Code = ".$mailchimp_lists->errorCode. "<br>" . "Message = ".$mailchimp_lists->errorMessage;
	} else {
		print_r($subscriber);
		if (!empty($subscriber['leid'])) {
			$body = $email . " subscribed to MailChimp with success.";
		} else {
			$body = $email . " failed to subscribe to MailChimp.";
		}
	}
}

echo "<h2>".$body."</h2>";
?>
<?php phpinfo() ?>
</body>
</HTML>
