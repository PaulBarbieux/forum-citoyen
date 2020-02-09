<DIV id="PanelWarningCookie" class="panel panel-warning">
	<DIV class="panel-body">
		<P>En poursuivant votre navigation sur ce site, vous acceptez l’utilisation de cookies.</P>
		<P>Si votre navigateur bloque les cookies, ce site ne fonctionnera pas correctement.</P>
		<P>Voir notre <a href="/privacy.php">politique de protection des données</a>.</P>
		<P><BUTTON id="CloseWarningCookie" type="button" class="btn btn-success">J'ai compris</BUTTON></P>
	</DIV>
</DIV>
<SCRIPT type="text/javascript">
jQuery(document).ready(function(){
	$("#CloseWarningCookie").click(function(){
		$.pgwCookie({ name: 'warning_cookie', value: 'done', expires: 720, path: "/" });
		$("#PanelWarningCookie").fadeOut(300);
	});
});
</SCRIPT>