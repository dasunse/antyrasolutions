<?php

class Maduranga_Amex_Model_Mysql4_Amex extends Mage_Core_Model_Mysql4_Abstract{
	/* (non-PHPdoc)
	 * @see Mage_Core_Model_Resource_Abstract::__construct()
	 */
	public function _construct() {
		// TODO: Auto-generated method stub
		$this->_init('amex/amex', 'amex_id');
	}
}
