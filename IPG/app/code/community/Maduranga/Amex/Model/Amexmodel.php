<?php
class Maduranga_Amex_Model_Amexmodel extends Mage_Payment_Model_Method_Abstract {
	protected $_code = 'amex';

	protected $_isInitializeNeeded      = true;
	protected $_canUseInternal          = true;
	protected $_canUseForMultishipping  = false;

	public function getOrderPlaceRedirectUrl() {
		return Mage::getUrl('amex/payment/redirect', array('_secure' => true));
	}

	
}