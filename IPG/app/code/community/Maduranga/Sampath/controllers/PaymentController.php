<?php
ob_start();
class Maduranga_Sampath_PaymentController extends Mage_Core_Controller_Front_Action {
	
	protected $order = null;
	protected $orderId = null;
    
	public function redirectAction() {
	   
        $this->order = new Mage_Sales_Model_Order();
		$this->orderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
		$this->order->loadByIncrementId($this->orderId);
        
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client/GatewayClient.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.config/ClientConfig.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.component/RequestHeader.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.component/CreditCard.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.component/TransactionAmount.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.component/Redirect.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.facade/BaseFacade.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.facade/Payment.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.payment/PaymentInitRequest.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.payment/PaymentInitResponse.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.root/PaycorpRequest.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.utils/IJsonHelper.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.helpers/PaymentInitJsonHelper.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.utils/HmacUtils.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.utils/CommonUtils.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.utils/RestClient.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.enums/TransactionType.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.enums/Version.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.enums/Operation.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.facade/Vault.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.facade/Report.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.facade/AmexWallet.php');
        
        date_default_timezone_set('Asia/Colombo');
       // error_reporting(E_ALL);
       /// ini_set('display_errors', 1);
        /*------------------------------------------------------------------------------
        STEP1: Build ClientConfig object
        ------------------------------------------------------------------------------*/
        $ClientConfig = new ClientConfig();
        $ClientConfig->setServiceEndpoint(Mage::getStoreConfig('payment/sampath/gatewayurl'));
        $ClientConfig->setAuthToken(Mage::getStoreConfig('payment/sampath/authtoken'));
        $ClientConfig->setHmacSecret(Mage::getStoreConfig('payment/sampath/hmacsecret'));
        $ClientConfig->setValidateOnly(FALSE);
        /*------------------------------------------------------------------------------
        STEP2: Build Client object
        ------------------------------------------------------------------------------*/
        $Client = new GatewayClient($ClientConfig);
        /*------------------------------------------------------------------------------
        STEP3: Build PaymentInitRequest object
        ------------------------------------------------------------------------------*/
        $initRequest = new PaymentInitRequest();
        $initRequest->setClientId(Mage::getStoreConfig('payment/sampath/clientid'));
        $initRequest->setTransactionType(TransactionType::$PURCHASE);
        $initRequest->setClientRef($this->orderId);
        $initRequest->setComment("Sampath Payment, <br>Order #".$this->orderId);
        $initRequest->setTokenize(TRUE);
        $initRequest->setExtraData(array( "order_id" => $this->orderId));
       /// $initRequest->setCssLocation1( Mage::getDesign()->getSkinUrl('css/sampath_payments.css'));
        // sets transaction-amounts details (all amounts are in cents)
        $transactionAmount = new TransactionAmount();
        $transactionAmount->setTotalAmount(0);
        $transactionAmount->setServiceFeeAmount(0);
        $transactionAmount->setPaymentAmount($this->order->getGrandTotal()*100);
        $transactionAmount->setCurrency($this->order->getOrderCurrencyCode());
        $initRequest->setTransactionAmount($transactionAmount);
        // sets redirect settings
        $redirect = new Redirect();
        $redirect->setReturnUrl(Mage::getUrl('sampath/payment/response/'));
        $redirect->setReturnMethod("GET");
        $initRequest->setRedirect($redirect);
        
        /*------------------------------------------------------------------------------
        STEP4: Process PaymentInitRequest object
        ------------------------------------------------------------------------------*/
        

        $initResponse = $Client->payment()->init($initRequest);

        /*------------------------------------------------------------------------------
        STEP5: Extract PaymentInitResponse object
        ------------------------------------------------------------------------------*/
        /*
        echo '<br><br>PCW Payment-Init Respopnse: --------------------------------------';
        echo '<br>Req Id : ' . $initResponse->getReqid();
        echo '<br>Payment Page Url : ' . $initResponse->getPaymentPageUrl();
        echo '<br>Expire At : ' . $initResponse->getExpireAt();
        echo '<br>------------------------------------------------------------------<br>';
        */
         //echo $initResponse->getPaymentPageUrl();
       // $url = $initResponse->getPaymentPageUrl();
        
       // echo $url;
        $this->_redirectUrl($initResponse->getPaymentPageUrl());
        
        
      /*  $url = $initResponse->getPaymentPageUrl();
        Mage::app()->getFrontController()->getResponse()->setRedirect($url);
        
      /*  echo '<form method="post" action="'.$initResponse->getPaymentPageUrl().'" >';
        //$this->_redirectUrl($initResponse->getPaymentPageUrl());
        echo '<input type="submit" name="submit" id="submit" value="Click here if you are not redirected to the Payment gateway withing 10 seconds..."/>';
        //header('location:'.$initResponse->getPaymentPageUrl());
		echo '</form>';
		echo '<script language="javascript">';
		///echo 'document.getElementById("submit").click();';
		echo '</script>';*/
	}
	
	
	public function responseAction() {
	   
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client/GatewayClient.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.config/ClientConfig.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.component/RequestHeader.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.component/CreditCard.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.component/TransactionAmount.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.component/Redirect.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.facade/BaseFacade.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.facade/Payment.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.root/PaycorpRequest.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.root/PaycorpResponse.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.payment/PaymentCompleteRequest.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.payment/PaymentCompleteResponse.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.utils/IJsonHelper.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.helpers/PaymentCompleteJsonHelper.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.utils/HmacUtils.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.utils/CommonUtils.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.utils/RestClient.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.enums/TransactionType.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.enums/Version.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.enums/Operation.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.facade/Vault.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.facade/Report.php');
        require_once(Mage::getBaseDir('lib') . '/sampath/au.com.gateway.client.facade/AmexWallet.php');
        
        date_default_timezone_set('Asia/Colombo');
        //error_reporting(E_ALL);
        ///ini_set('display_errors', 1);
        
		/*------------------------------------------------------------------------------
        STEP1: Build ClientConfig object
        ------------------------------------------------------------------------------*/
        $ClientConfig = new ClientConfig();
        $ClientConfig->setServiceEndpoint(Mage::getStoreConfig('payment/sampath/gatewayurl'));
        $ClientConfig->setAuthToken(Mage::getStoreConfig('payment/sampath/authtoken'));
        $ClientConfig->setHmacSecret(Mage::getStoreConfig('payment/sampath/hmacsecret'));
        $ClientConfig->setValidateOnly(FALSE);
        /*------------------------------------------------------------------------------
        STEP2: Build Client object
        ------------------------------------------------------------------------------*/
        
        $Client = new GatewayClient($ClientConfig);
        /*------------------------------------------------------------------------------
        STEP3: Build PaymentCompleteRequest object
        ------------------------------------------------------------------------------*/
        $completeRequest = new PaymentCompleteRequest();
        $completeRequest->setClientId(Mage::getStoreConfig('payment/sampath/clientid'));
        $completeRequest->setReqid($_GET['reqid']);
        /*------------------------------------------------------------------------------
        STEP4: Process PaymentCompleteRequest object
        ------------------------------------------------------------------------------*/
        
        $completeResponse = $Client->payment()->complete($completeRequest);
		
		Mage::getSingleton('core/session')->setSampathMessage('Payment Declined - Please try an alternative card.');
        
		$orderId = null;
		$ResponseCode = 0;
		if ($completeResponse) {
			
			if($completeResponse->getTxnReference()){
				
				$ResponseCode = $completeResponse->getResponseCode();
				if($ResponseCode == '00'){
					$validated = true;
                    $ResponseCode_val = 1;\
					Mage::getSingleton('core/session')->setSampathMessage('Transaction was processed successfully.');
				}else{
					$validated = false;
                    $ResponseCode_val = 0;
					Mage::getSingleton('core/session')->setSampathMessage('Payment Declined - Please try an alternative card.');
				}
				
				$orderId = $completeResponse->getClientRef(); 
				$ReasonCodeDesc = $completeResponse->getResponseText();
				/*
				$next_msg = array(
					'00' =>	'TRANSACTION APPROVED',
					'91' => 'BANK NOT AVAILABLE',
					'92' => 'BANK NOT FOUND',
					'A4' => 'CONNECTION ERROR',
					'C5' => 'SYSTEM ERROR',
					'T3' => 'TRANSACTION REJECTED',
					'T4' => 'CONTACT ACQUIRING BANK',
					'U9' => 'NO RESPONSE',
					'X1' => 'GATEWAY UNAVAILABLE',
					'X3' => 'NETWORK ERROR',
					'-1' => '<various>',
					'C0' => 'CARTRIDGE ERROR',
					'A6' => 'SERVER BUSY',
				);
				
				
				if(isset($next_msg[ResponseCode])){
					Mage::getSingleton('core/session')->setSampathMessage($next_msg[ResponseCode]);
					if($ResponseCode_val){
						Mage::getSingleton('core/session')->addSuccess($next_msg[ResponseCode]); 
					}else{
						Mage::getSingleton('core/session')->addError($next_msg[ResponseCode]); 
					}
				}
				*/
			}else{
				$validated = false;
			}
		
			if ($validated) {
			 
				$order = Mage::getModel ('sales/order');
				$order->loadByIncrementId ( $orderId );
				
				$string = '';
				$string .= 'Txn Reference :- '.$completeResponse->getTxnReference().'<br/>';
				$string .= $completeResponse->getCreditCard()->getNumber().'-('.$completeResponse->getCreditCard()->getType().')<br>';
                
				$order->setState( Mage_Sales_Model_Order::STATE_PROCESSING, true, 'Gateway has authorized the payment.<br/>'.$ReasonCodeDesc.'<br/>'.$string );
				
                $order->sendNewOrderEmail();
				$order->setEmailSent(true);
				
				
				$order->save ();
                
				Mage::getSingleton ('checkout/session')->unsQuoteId ();
				Mage_Core_Controller_Varien_Action::_redirect ('checkout/onepage/success', array ( '_secure' => true  ) );
				
			} else {
			     
				if($orderId){
					
				}else{
					$orderId = Mage::getSingleton ( 'checkout/session' )->getLastRealOrderId();
				}
				
				if (Mage::getSingleton ( 'checkout/session' )->getLastRealOrderId ()) {
					$order = Mage::getModel ( 'sales/order' )->loadByIncrementId ( $orderId );
					if ($order->getId ()) {
						$order->cancel ()->setState ( Mage_Sales_Model_Order::STATE_CANCELED, true, 'Gateway has declined the payment.<br/>'.$ReasonCodeDesc  )->save ();
					}
				}
				Mage_Core_Controller_Varien_Action::_redirect ( 'checkout/onepage/failure', array ( '_secure' => true  ) );
				
			}
		} else {
            Mage_Core_Controller_Varien_Action::_redirect ( '' );
        }
	}
	
}

ob_end_flush(); 