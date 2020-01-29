jQuery(document).ready(function(){
	// Confirmation lors d'une suppression. La balise A doit contenir un attribut label-confirm à afficher dans le popup.
	$("A._delete").click(function(){
		if (confirm("Confirmez-vous la suppression de \""+$(this).attr('label-confirm')+"\" ?")) {
			return true;
		} else {
			return false;
		}
	});
});	

// Google analytics
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-84277277-1', 'auto');
ga('send', 'pageview');