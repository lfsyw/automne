<?php
// +----------------------------------------------------------------------+
// | Automne (TM)														  |
// +----------------------------------------------------------------------+
// | Copyright (c) 2000-2010 WS Interactive								  |
// +----------------------------------------------------------------------+
// | Automne is subject to version 2.0 or above of the GPL license.		  |
// | The license text is bundled with this package in the file			  |
// | LICENSE-GPL, and is available through the world-wide-web at		  |
// | http://www.gnu.org/copyleft/gpl.html.								  |
// +----------------------------------------------------------------------+
// | Author: Sébastien Pauchet <sebastien.pauchet@ws-interactive.fr>      |
// +----------------------------------------------------------------------+

/**
  * Class CMS_XMLTag_page
  *
  * This script aimed to manage atm-page tags. it extends CMS_XMLTag
  *
  * @package Automne
  * @subpackage standard
  * @author Sébastien Pauchet <sebastien.pauchet@ws-interactive.fr>
  */
class CMS_XMLTag_page extends CMS_XMLTag
{
	/**
	 * Default tag context
	 * @var string the default tag context
	 * @access public
	 */
	protected $_context = CMS_XMLTag::HTML_CONTEXT;
	
	/**
	  * Constructor.
	  *
	  * @param string $name The name of the tag
	  * @param array(string) $attributes The tag attributes.
	  * @return void
	  * @access public
	  */
	public function __construct($name, $attributes, $children, $parameters) {
		if (isset($parameters['context']) && $parameters['context']) {
			$this->_context = $parameters['context'];
		}
		parent::__construct($name, $attributes, $children, $parameters);
	}
	
	/**
	  * Compute the tag
	  *
	  * @return string the PHP / HTML content computed
	  * @access private
	  */
	protected function _compute() {
		if ($this->_parameters['context'] == CMS_XMLTag::HTML_CONTEXT) {
			if (!isset($this->_computeParams['visualization']) || !isset($this->_computeParams['object']) || !($this->_computeParams['object'] instanceof CMS_page)) {
				return '';
			}
			$public = ($this->_computeParams['visualization'] == PAGE_VISUALMODE_PRINT || $this->_computeParams['visualization'] == PAGE_VISUALMODE_HTML_PUBLIC);
			return CMS_tree::getPageValue($this->_computeParams['object']->getID(), $this->_attributes['name'], $public);
		} else {
			return '$content .= CMS_tree::getPageValue($parameters[\'pageID\'], \''.$this->_attributes['name'].'\', (isset($public_search) ? $public_search : false));';
		}
	}
}
?>