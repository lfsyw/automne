<?php
if (file_exists($_SERVER['DOCUMENT_ROOT'].'/html/29.php')) {
	$cms_page_included = true;
	require($_SERVER['DOCUMENT_ROOT'].'/html/29.php');
} else {
	header('Location: /404.php');
	exit;
}
?>