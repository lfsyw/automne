<?php //Generated on Tue, 25 Nov 2008 16:53:40 +0100 by Automne (TM) 4.0.0a
if (!isset($cms_page_included) && !$_POST && !$_GET) {
	header('HTTP/1.x 301 Moved Permanently', true, 301);
	header('Location: http://automne4/web/fr/print-11-gestion-de-contenu.php');
	exit;
}
require_once($_SERVER["DOCUMENT_ROOT"]."/cms_rc_frontend.php");
 ?><?php if (defined('APPLICATION_XHTML_DTD')) echo APPLICATION_XHTML_DTD."\n";  ?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr">
<head>
	<title>Automne 4 : Gestion de contenu</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<link rel="stylesheet" type="text/css" href="/css/print.css" />
</head>
<body>
<h1>Gestion de contenu</h1>
<h3>

		
&raquo;
&nbsp;
Fonctionnalit�s
		

		
&raquo;
&nbsp;
Gestion de contenu
		

</h3>



	
	
		<div class="text"><p>Dans cette page doit se trouver des explications sur :</p><ul><li>Principe de &quot;Page&quot;</li><li>Principe de &quot;Mod&egrave;le&quot;</li><li>Principe de &quot;Rang&eacute;e&quot;</li></ul></div>
	
	<div class="spacer"></div>
<br />
<hr />
<div align="center">
	<small>
		Derni�re mise � jour le 14/11/2008<br />
		
				Page  "Gestion de contenu" (http://automne4/web/fr/11-gestion-de-contenu.php)
				
<br />
		Tir� du site http://<?php echo $_SERVER["HTTP_HOST"];   ?>
	</small>
</div>
<script language="JavaScript">window.print();</script>
<?php if (SYSTEM_DEBUG && STATS_DEBUG) {view_stat(); if (VIEW_SQL && isset($_SESSION["cms_context"]) && is_object($_SESSION["cms_context"])) {save_stat();}}  ?>
</body>
</html>