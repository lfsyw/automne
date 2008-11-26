<?php

/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Automne (TM)														  |
// +----------------------------------------------------------------------+
// | Copyright (c) 2000-2009 WS Interactive								  |
// +----------------------------------------------------------------------+
// | Automne is subject to version 2.0 or above of the GPL license.		  |
// | The license text is bundled with this package in the file			  |
// | LICENSE-GPL, and is available through the world-wide-web at		  |
// | http://www.gnu.org/copyleft/gpl.html.								  |
// +----------------------------------------------------------------------+
// | Author: Sébastien Pauchet <sebastien.pauchet@ws-interactive.fr>      |
// +----------------------------------------------------------------------+
//
// $Id: loadingDialog.php,v 1.1.1.1 2008/11/26 17:12:06 sebastien Exp $

/**
  * Class CMS_LoadingDialog
  *
  * Interface generation : send texts in real-time to user navigator.
  *
  * @package CMS
  * @subpackage dialogs
  * @author Sébastien Pauchet <sebastien.pauchet@ws-interactive.fr>
  */
class CMS_LoadingDialog extends CMS_dialog
{
	function startLoadingMode()
	{
		@set_time_limit(0);
		@ini_set('output_buffering','Off');
		@ob_end_flush();
		CMS_LoadingDialog::sendToUser('<div style="margin-left:15px;">');
	}
	
	/**
	  * Send a text and flush
	  *
	  * @var string $text : the text to send
	  * @access public
	  */
	function sendToUser($text)
	{
		echo $text;
		@ob_start();
		@ob_end_clean();
		@flush();
		@ob_end_flush();
		@usleep(1);
	}
	
	/**
	  * Send a text, flush and close dialog
	  *
	  * @var string $text : the text to send
	  * @access public
	  */
	function sendAndClose($text)
	{
		CMS_LoadingDialog::sendToUser($text);
		CMS_LoadingDialog::closeDialog();
	}
	
	/**
	  * Close dialog
	  *
	  * @access public
	  */
	function closeDialog()
	{
		echo '</div></body></html>';
		exit;
	}
}
?>