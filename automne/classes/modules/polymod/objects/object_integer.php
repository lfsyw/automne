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
// $Id: object_integer.php,v 1.1.1.1 2008/11/26 17:12:06 sebastien Exp $

/**
  * Class CMS_object_integer
  *
  * represent a simple integer object
  *
  * @package CMS
  * @subpackage module
  * @author Sébastien Pauchet <sebastien.pauchet@ws-interactive.fr>
  */

class CMS_object_integer extends CMS_object_common
{
	/**
  * Polymod Messages
  */
	const MESSAGE_OBJECT_INTEGER_LABEL = 177;
	const MESSAGE_OBJECT_INTEGER_DESCRIPTION = 178;
	const MESSAGE_OBJECT_INTEGER_PARAMETER_CANBENULL = 179;
	const MESSAGE_OBJECT_INTEGER_PARAMETER_CANBENEGATIVE = 180;
	const MESSAGE_OBJECT_INTEGER_PARAMETER_UNIT = 417;
  	const MESSAGE_OBJECT_INTEGER_PARAMETER_UNIT_DESC = 418;
  	const MESSAGE_OBJECT_INTEGER_PARAMETER_UNIT_DESCRIPTION = 419;
	/**
	  * object label
	  * @var integer
	  * @access private
	  */
	protected $_objectLabel = self::MESSAGE_OBJECT_INTEGER_LABEL;
	
	/**
	  * object description
	  * @var integer
	  * @access private
	  */
	protected $_objectDescription = self::MESSAGE_OBJECT_INTEGER_DESCRIPTION;
	
	/**
	  * all subFields definition
	  * @var array(integer "subFieldID" => array("type" => string "(string|boolean|integer|date)", "required" => boolean, 'internalName' => string [, 'externalName' => i18nm ID]))
	  * @access private
	  */
	protected $_subfields = array(0 => array(
										'type' 			=> 'integer',
										'required' 		=> false,
										'internalName'	=> 'integer',
									),
							);
	
	/**
	  * all subFields values for object
	  * @var array(integer "subFieldID" => mixed)
	  * @access private
	  */
	protected $_subfieldValues = array(0 => '');
	
	/**
	  * all parameters definition
	  * @var array(integer "subFieldID" => array("type" => string "(string|boolean|integer|date)", "required" => boolean, 'internalName' => string [, 'externalName' => i18nm ID]))
	  * @access private
	  */
	protected $_parameters = array(0 => array(
										'type' 			=> 'boolean',
										'required' 		=> false,
										'internalName'	=> 'canBeNull',
										'externalName'	=> self::MESSAGE_OBJECT_INTEGER_PARAMETER_CANBENULL,
									),
							 1 => array(
										'type' 			=> 'boolean',
										'required' 		=> false,
										'internalName'	=> 'canBeNegative',
										'externalName'	=> self::MESSAGE_OBJECT_INTEGER_PARAMETER_CANBENEGATIVE,
									),
							2 => array(
										'type'                  => 'string',
										'required'              => false,
										'internalName'  => 'unit',
										'externalName'  => MESSAGE_OBJECT_INTEGER_PARAMETER_UNIT,
										'description'   => MESSAGE_OBJECT_INTEGER_PARAMETER_UNIT_DESC,
									),
							);
	
	/**
	  * all subFields values for object
	  * @var array(integer "subFieldID" => mixed)
	  * @access private
	  */
	protected $_parameterValues = array(0 => false, 1 => false, 2 => '');
	
	/**
	  * Constructor.
	  * initialize object.
	  *
	  * @param array $datas DB object values : array(integer "subFieldID" => mixed)
	  * @param CMS_object_field reference
	  * @param boolean $public values are public or edited ? (default is edited)
	  * @return void
	  * @access public
	  */
	function __construct($datas=array(), &$field, $public=false)
	{
		parent::__construct($datas, $field, $public);
	}
	
	/**
	  * set object Values
	  *
	  * @param array $values : the POST result values
	  * @param string prefixname : the prefix used for post names
	  * @return boolean true on success, false on failure
	  * @access public
	  */
	function setValues($values,$prefixName) {
		$params = $this->getParamsValues();
		foreach ($this->_subfields as $subFieldID => $subFieldDefinition) {
			if (is_object($this->_subfieldValues[$subFieldID])) {
				if ($values[$prefixName.$this->_field->getID().'_'.$subFieldID] || $values[$prefixName.$this->_field->getID().'_'.$subFieldID] === '0') {
					//check value according to parameters
					
					//must be numeric
					if (!is_numeric($values[$prefixName.$this->_field->getID().'_'.$subFieldID])) {
						return false;
					}
					//check canBeNull parameter
					if (!$params['canBeNull'] && $values[$prefixName.$this->_field->getID().'_'.$subFieldID] === '0') {
						return false;
					}
					//check canBeNegative parameter
					if (!$params['canBeNegative'] && $values[$prefixName.$this->_field->getID().'_'.$subFieldID] < 0) {
						return false;
					}
					if (!$this->_subfieldValues[$subFieldID]->setValue($values[$prefixName.$this->_field->getID().'_'.$subFieldID])) {
						return false;
					}
				} else {
					$this->_subfieldValues[$subFieldID]->setValue(null);
				}
			}
		}
		return true;
	}
	
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
	    'class'         => 'admin_input_text',
	    'prefix'        =>      $prefixName,
	    'size'          => 15,
	    'form'          => 'frmitem',
	);
	$html .= $this->getInput($fieldID, $language, $inputParams);
	$html .= '</td></tr>'."\n";
	return $html;
    }

    /**
      * get object values structure available with getValue method
      *
      * @return multidimentionnal array : the object values structure
      * @access public
      */
    function getStructure() {
		$structure = parent::getStructure();
		unset($structure['value']);
		$structure['unit'] = '';
		return $structure;
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
			case 'unit':
				//get field parameters
				$params = $this->getParamsValues();
				return ($params['unit']) ? $params['unit'] : '';
			break;
			default:
				return parent::getValue($name, $parameters);
			break;
		}
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
			unset($inputParams['prefix']);
		} else {
			$prefixName = '';
		}
		//serialize all htmlparameters
		$htmlParameters = $this->serializeHTMLParameters($inputParams);
		$html = '';
		if (is_object($this->_subfieldValues[0])) {
			//create fieldname
			$fieldName = $prefixName.$this->_field->getID().'_0';
			//append field id to html field parameters (if not already exists)
			$htmlParameters .= (!isset($inputParams['id'])) ? ' id="'.$prefixName.$this->_field->getID().'_0"' : '';
			//create field value
			$value = ($this->_subfieldValues[0]->getValue() || $params['canBeNull']) ? $this->_subfieldValues[0]->getValue() : '';
			//then create field HTML
			$html .= ($html) ? '<br />':'';
			$html .= '<input type="text"'.$htmlParameters.' name="'.$prefixName.$this->_field->getID().'_0" value="'.$value.'" />'."\n";
			if ($params['unit']) {
				$html .= '&nbsp;<small>'.$params['unit'].'</small>';
			}
			if (POLYMOD_DEBUG) {
				$html .= ' <span class="admin_text_alert">(Field : '.$this->_field->getID().' - SubField : 0)</span>';
			}
		}
		//append html hidden field which store field name
		if ($html) {
			$html .= '<input type="hidden" name="polymodFields['.$this->_field->getID().']" value="'.$this->_field->getID().'" />';
		}
		return $html;
    }

    /**
      * get labels for object structure and functions
      *
      * @return array : the labels of object structure and functions
      * @access public
      */
    function getLabelsStructure(&$language) {
		$labels = parent::getLabelsStructure($language);
		$params = $this->getParamsValues();
		unset($labels['structure']['value']);
		if($params['unit']){
			$labels['structure']['unit'] = $language->getMessage(MESSAGE_OBJECT_INTEGER_PARAMETER_UNIT_DESCRIPTION,array($params['unit']) ,MOD_POLYMOD_CODENAME);
		}
		return $labels;
    }
}

?>