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
// | Author: Antoine Pouch <antoine.pouch@ws-interactive.fr>              |
// +----------------------------------------------------------------------+
//
// $Id: clientspacescatalog.php,v 1.1.1.1 2008/11/26 17:12:06 sebastien Exp $

/**
  * Class CMS_moduleClientspace_standard_catalog
  *
  * Represents a collection of standard client spaces
  *
  * @package CMS
  * @subpackage module
  * @author Antoine Pouch <antoine.pouch@ws-interactive.fr>
  */

class CMS_moduleClientspace_standard_catalog extends CMS_grandFather
{
	/**
	  * Get a clientspace byt its template DB ID and tag ID
	  *
	  * @param integer $templateID the DB ID of the template
	  * @param integer $tagID the tag ID of the client space tag
	  * @param boolean $editionMode Is this a request made during editing client space ?
	  * @return boolean true on success, false on failure
	  * @access public
	  */
	function getByTemplateAndTagID($templateID, $tagID, $editionMode = false)
	{
		return new CMS_moduleClientspace_standard($templateID, $tagID, $editionMode);
	}
	
	/**
	  * Move the clientSpaces data from one location to another for a template
	  *
	  * @param integer $tagID the tag ID of the client space tag
	  * @param string $locationFrom The starting location, among the available RESOURCE_DATA_LOCATION
	  * @param string $locationTo The ending location, among  the available RESOURCE_DATA_LOCATION
	  * @param boolean $copyOnly If set to true, the deletion from the originating tables and dirs won't occur
	  * @return boolean true on success, false on failure
	  * @access public
	  */
	function moveClientSpaces($templateID, $locationFrom, $locationTo, $copyOnly = false)
	{
		if (!SensitiveIO::isInSet($locationFrom, CMS_resource::getAllDataLocations())
			|| !SensitiveIO::isInSet($locationTo, CMS_resource::getAllDataLocations())) {
			CMS_grandFather::raiseError("Locations are not in the set");
			return false;
		}
		
		switch ($locationFrom) {
		case RESOURCE_DATA_LOCATION_ARCHIVED:
			$table_from = "mod_standard_clientSpaces_archived";
			break;
		case RESOURCE_DATA_LOCATION_DELETED:
			$table_from = "mod_standard_clientSpaces_deleted";
			break;
		case RESOURCE_DATA_LOCATION_PUBLIC:
			$table_from = "mod_standard_clientSpaces_public";
			break;
		case RESOURCE_DATA_LOCATION_EDITED:
			$table_from = "mod_standard_clientSpaces_edited";
			break;
		case RESOURCE_DATA_LOCATION_EDITION:
			$table_from = "mod_standard_clientSpaces_edition";
			break;
		}
		
		switch ($locationTo) {
		case RESOURCE_DATA_LOCATION_ARCHIVED:
			$table_to = "mod_standard_clientSpaces_archived";
			break;
		case RESOURCE_DATA_LOCATION_DELETED:
			$table_to = "mod_standard_clientSpaces_deleted";
			break;
		case RESOURCE_DATA_LOCATION_PUBLIC:
			$table_to = "mod_standard_clientSpaces_public";
			break;
		case RESOURCE_DATA_LOCATION_EDITED:
			$table_to = "mod_standard_clientSpaces_edited";
			break;
		case RESOURCE_DATA_LOCATION_EDITION:
			$table_to = "mod_standard_clientSpaces_edition";
			break;
		}
		
		//delete all in the destination table just incase and insert
		if ($locationTo != RESOURCE_DATA_LOCATION_DEVNULL) {
			$sql = "
				delete from
					".$table_to."
				where
					template_cs='".$templateID."'
			";
			$q = new CMS_query($sql);
				
			$sql = "
				insert into
					".$table_to."
					select
						*
					from
						".$table_from."
					where
						template_cs='".$templateID."'
			";
			$q = new CMS_query($sql);
		}
		if (!$copyOnly) {
			//delete from the starting table
			$sql = "
				delete from
					".$table_from."
				where
					template_cs='".$templateID."'
			";
			$q = new CMS_query($sql);
		}
		return true;
	}
}
?>