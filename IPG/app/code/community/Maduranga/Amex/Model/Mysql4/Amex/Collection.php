<?php

class Maduranga_Amex_Model_Mysql4_Amex_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract{
	/* (non-PHPdoc)
	 * @see Mage_Core_Model_Resource_Db_Collection_Abstract::__construct()
	 */
	public function _construct() {
		// TODO: Auto-generated method stub
		parent::_construct();
		$this->_init('amex/amex');
	}
}

