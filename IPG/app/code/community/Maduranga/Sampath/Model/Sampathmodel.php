<?php
class Maduranga_Sampath_Model_Sampathmodel extends Mage_Payment_Model_Method_Abstract {
	protected $_code = 'sampath';

	protected $_isInitializeNeeded      = true;
	protected $_canUseInternal          = true;
	protected $_canUseForMultishipping  = false;

	public function getOrderPlaceRedirectUrl() {
		return Mage::getUrl('sampath/payment/redirect', array('_secure' => true));
	}

	
}