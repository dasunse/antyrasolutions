<?php


class Maduranga_Amex_Block_Amex extends Mage_Core_Block_Template{
	
	protected $order = null;
	protected $orderId = null;
	
	function start(){
		$this->order = new Mage_Sales_Model_Order();
		$this->orderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
		$this->order->loadByIncrementId($this->orderId);
	}
	
	function __construct(){
		$this->order = new Mage_Sales_Model_Order();
		$this->orderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
		$this->order->loadByIncrementId($this->orderId);
	}
	
	protected function getMerRespURL(){
		
		return $url = Mage::getUrl('amex/payment/response/',array('_secure'=>true));
		/*if(strpos($url,'https')){
			return $url;
		}else{
			return str_replace("http","https", $url);
		}*/
	}
	
	protected function getSignature(){
		$pass = $this->getPass().$this->getMerId().$this->getAcqId().$this->getOrderID().$this->getPurchaseAmt().$this->getPurchaseCurrency();
		return $enc = base64_encode(pack('H*', sha1($pass)));
	}
	
	protected function getOrderID(){
		return $this->orderId;
	}
	
	protected function getPurchaseAmt(){ 
		
		$num = $this->order->getGrandTotal();
		
		$num_array = explode(".",$num);
		$num_2 = '0.'.$num_array[1];
		$num_3 = round($num_2, 2);
		
		if($num_3){
			$num2_array = explode(".",$num_3);
			$num_4 =str_pad($num2_array[1], 2, 0, STR_PAD_RIGHT);
		}else{
			$num_4 = '00';
		}

		return str_pad($num_array[0].$num_4, 12, 0, STR_PAD_LEFT);		
		
	}
	
	
	protected function getPurchaseCurrency(){
		//return '144';
		$connection = Mage::getSingleton('core/resource')->getConnection('maduranga_countries');
		$result = $connection->fetchAll("SELECT * FROM `maduranga_countries` WHERE `Ccy` = '".$this->order->getOrderCurrencyCode()."' ");
		
		return $result[0]['CcyNbr'];
	}
	
	protected function getShipToLastName(){
		 $customer = Mage::getSingleton('customer/session')->getCustomer(); 
		 return $customer->getLastname();
	}
	
	protected function getTestFlag(){
		return Mage::getStoreConfig('payment/amex/amextestmod');
	}
	
	protected function getMerId(){
		return Mage::getStoreConfig('payment/amex/merid');
	}
	
	protected function getTitle(){
		return Mage::getStoreConfig('payment/amex/title');
	}
	
	protected function getVersion(){
		return Mage::getStoreConfig('payment/amex/version');
	}
	
	protected function getAcqId(){
		return Mage::getStoreConfig('payment/amex/acqid');
	}
	
	protected function getPass(){
		return Mage::getStoreConfig('payment/amex/pass');
	}
	
	protected function getGatewayURL(){
		return Mage::getStoreConfig('payment/amex/gatewayurl');
	}	
	
}