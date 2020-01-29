<FOOTER>
	<DIV class="footer-liens-divers">
		<DIV class="container">
			<DIV class="row">
				<DIV class="col-xs-6">
					<UL class="nav nav-pills">
						<LI><A href="/contact.php"><i class="fa fa-envelope" aria-hidden="true"></i></A></LI>
						<LI><A href="https://www.facebook.com" target="_blank"><i class="fa fa-facebook-official" aria-hidden="true"></i></A></LI>
						<LI><A href="https://twitter.com" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></A></LI>
					</UL>
				</DIV>
				<DIV style="float:right">
					<UL class="nav nav-pills text-right">
						<LI><A href="http://www.extrapaul.be" target="_blank"><i>&nbsp;</i> Site conçu par ExtraPaul</A></LI>
					</UL>
				</DIV>
			</DIV>
		</DIV>
	</DIV>
</FOOTER>
<?php
/*
	Avertissement concernant les cookies
*/
if (!isset($_COOKIE['warning_cookie'])) {
	require $_SERVER['DOCUMENT_ROOT']."/includes/modalWarningCookie.php";
}
?>