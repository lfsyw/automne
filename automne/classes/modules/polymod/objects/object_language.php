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
// $Id: object_language.php,v 1.1.1.1 2008/11/26 17:12:06 sebastien Exp $

/**
  * Class CMS_object_language
  *
  * represent a simple language object
  *
  * @package CMS
  * @subpackage module
  * @author Sébastien Pauchet <sebastien.pauchet@ws-interactive.fr>
  */

class CMS_object_language extends CMS_object_common
{
	/**
  * Standard Messages
  */
	const MESSAGE_OBJECT_LANGUAGE_EMPTY_OBJECTS_SET = 265;
	const MESSAGE_OBJECT_LANGUAGE_CHOOSE_OBJECT = 1132;
	
	/**
	  * Polymod Messages
	  */
	const MESSAGE_OBJECT_LANGUAGE_LABEL = 323;
	const MESSAGE_OBJECT_LANGUAGE_DESCRIPTION = 324;
	/**
	  * object label
	  * @var integer
	  * @access private
	  */
	protected $_objectLabel = self::MESSAGE_OBJECT_LANGUAGE_LABEL;
	
	/**
	  * object description
	  * @var integer
	  * @access private
	  */
	protected $_objectDescription = self::MESSAGE_OBJECT_LANGUAGE_DESCRIPTION;
	
	/**
	  * all subFields definition
	  * @var array(integer "subFieldID" => array("type" => string "(string|boolean|integer|date)", "required" => boolean, 'internalName' => string [, 'externalName' => i18nm ID]))
	  * @access private
	  */
	protected $_subfields = array(0 => array(
										'type' 			=> 'string',
										'required' 		=> false,
										'internalName'	=> 'language',
									),
							);
	
	/**
	  * all subFields values for object
	  * @var array(integer "subFieldID" => mixed)
	  * @access private
	  */
	protected $_subfieldValues = array(0 => '');
	
	/**
	  * get HTML admin (used to enter object values in admin)
	  *
	  * @param integer $fieldID, the current field id (only for poly object compatibility)
	  * @param CMS_language $language, the current admin language
	  * @param string prefixname : the prefix to use for post names
	  * @return string : the html admin
	  * @access public
	  */
	function getHTMLAdmin($fieldID, $language, $prefixName) {
		//is this field mandatory ?
		$mandatory = ($this->_field->getValue('required')) ? '<span class="admin_text_alert">*</span> ':'';
		//create html for each subfields
		$html = '<tr><td class="admin" align="right" valign="top">'.$mandatory.$this->getFieldLabel($language).'</td><td class="admin">'."\n";
		//add description if any
		if ($this->getFieldDescription($language)) {
			$html .= '<dialog-title type="admin_h3">'.$this->getFieldDescription($language).'</dialog-title><br />';
		}
		$inputParams = array(
			'class' 	=> 'admin_input_text',
			'prefix'	=>	$prefixName,
			'form'		=> 'frmitem',
		);
		$html .= $this->getInput($fieldID, $language, $inputParams);
		$html .= '</td></tr>'."\n";
		return $html;
	}
	
	/**
      * Return the needed form field tag for current object field
      *
      * @param array $values : parameters values array(parameterName => parameterValue) in :
      *     id : the form field id to set
      * @param multidimentionnal array $tags : xml2Array content of atm-function tag
      * @return string : the form field HTML tag
      * @access public
      */
	function getInput($fieldID, $language, $inputParams) {
		$params = $this->getParamsValues();
		if (isset($inputParams['prefix'])) {
			$prefixName = $inputParams['prefix'];
		} else {
			$prefixName = '';
		}
		//serialize all htmlparameters 
		$htmlParameters = $this->serializeHTMLParameters($inputParams);
		$html = '';
		//get module
		$module = CMS_poly_object_catalog::getModuleCodenameForField($this->_field->getID());
		// Get languages
		$a_all_languages = CMS_languagesCatalog::getAllLanguages($module);
		if (is_array($a_all_languages) && $a_all_languages) {
			// Hidden languages (from input param "hidden=fr,en,es")
			if(isset($inputParams['hidden'])){
				$hiddenLanguageCodes = explode(',',$inputParams['hidden']);
				foreach($hiddenLanguageCodes as $hiddenLanguageCode){
					if(isset($a_all_languages[$hiddenLanguageCode])) {
						unset($a_all_languages[$hiddenLanguageCode]);
					}
				}
			}
			$html .= '
			<select name="'.$prefixName.$this->_field->getID().'_0"'.$htmlParameters.'>
				<option value="0">'.$language->getMessage(self::MESSAGE_OBJECT_LANGUAGE_CHOOSE_OBJECT).'</option>';
			foreach($a_all_languages as $code => $aLanguage) {
				$selected = ($this->_subfieldValues[0]->getValue() == $code) ? ' selected="selected"':'';
				$html .= '<option value="'.$code.'"'.$selected.'>'.$aLanguage->getLabel().'</option>';
			}
			$html .= '</select>';
			
		} else {
			$html .= $language->getMessage(self::MESSAGE_OBJECT_LANGUAGE_EMPTY_OBJECTS_SET);
		}
		if (POLYMOD_DEBUG) {
			$html .= '<span class="admin_text_alert"> (Field : '.$fieldID.' - Value : '.$this->_subfieldValues[0]->getValue().')</span>';
		}
		//append html hidden field which store field name
		if ($html) {
			$html .= '<input type="hidden" name="polymodFields['.$this->_field->getID().']" value="'.$this->_field->getID().'" />';
		}
		return $html;
	}
	
	/**
	  * get object HTML description for admin search detail. Usually, the label.
	  *
	  * @return string : object HTML description
	  * @access public
	  */
	function getHTMLDescription() {
		if (!$this->_subfieldValues[0]->getValue()) {
			return '';
		}
		$language = CMS_languagesCatalog::getByCode($this->_subfieldValues[0]->getValue());
		if (!$language) {
			return '';
		}
		return $language->getLabel();
	}
	
	/**
	  * get object label : for this object, same as getHTMLDescription
	  *
	  * @return string : the language label
	  * @access public
	  */
	function getLabel() {
		return $this->getHTMLDescription();
	}
	
	/**
	  * get an object value
	  *
	  * @param string $name : the name of the value to get
	  * @param string $parameters (optional) : parameters for the value to get
	  * @return multidimentionnal array : the object values structure
	  * @access public
	  */
	function getValue($name, $parameters = '') {
		switch($name) {
			case 'label':
				return $this->getLabel();
			break;
			case 'value':
				return $this->_subfieldValues[0]->getValue();
			break;
			default:
				return parent::getValue($name, $parameters);
			break;
		}
	}
	
	/**
	  * Return a list of all objects names of given type
	  *
	  * @param boolean $public are the needed datas public ? /!\ Does not apply for this type of object
	  * @param array $searchConditions, search conditions to add. /!\ Does not apply for this type of object
	  * @return array(integer objectID => string objectName)
	  * @access public
	  * @static
	  */
	function getListOfNamesForObject($public = false, $searchConditions = array()) {
		//get module
		$module = CMS_poly_object_catalog::getModuleCodenameForField($this->_field->getID());
		// Get languages
		$a_all_languages = CMS_languagesCatalog::getAllLanguages($module);
		$languages = array();
		foreach ($a_all_languages as $code => $language) {
			$languages[$code] = $language->getLabel();
		}
		return $languages;
	}
	
	/**
	  * Return options tag list (for a select tag) of all languages
	  *
	  * @param array $values : parameters values array(parameterName => parameterValue) in :
	  * 	selected : the language code which is selected (optional)
	  * @param multidimentionnal array $tags : xml2Array content of atm-function tag (nothing for this one)
	  * @return string : options tag list
	  * @access public
	  */
	function selectOptions($values, $tags) {
		$all_languages = $this->getListOfNamesForObject();
		$return = "";
		if (is_array($all_languages) && $all_languages) {
			foreach ($all_languages as $languageCode => $languageLabel) {
				$selected = ($languageCode == $values['selected']) ? ' selected="selected"':'';
				$return .= '<option title="'.$languageLabel.'" value="'.$languageCode.'"'.$selected.'>'.$languageLabel.'</option>';
			}
		}
		return $return;
	}
}

?>