<?php
ob_start();
class Maduranga_Amex_PaymentController extends Mage_Core_Controller_Front_Action {
	
	protected $order = null;
	protected $orderId = null;
    
	public function redirectAction() {
	   
        $this->order = new Mage_Sales_Model_Order();
	$this->orderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
	$this->order->loadByIncrementId($this->orderId);
        
        
        date_default_timezone_set('Asia/Colombo');
        
        $IPGClientIP = "127.0.0.1";
        $IPGClientPort = "10000";

        $ERRNO = "";
        $ERRSTR = "";
        $SOCKET_TIMEOUT = 2;
        $IPGSocket = "";

        $error_message = "";
        $invoice_sent_error = "";
        $encryption_ERR = "";

        $Invoice = "";
        $EncryptedInvoice = "";

        $IPGServerURL = "https://www.ipayamex.lk/ipg/servlet_pay";
        
        /*********************************************************************/
        $currencyCode = $this->order->getOrderCurrencyCode();
        $MerchantID = "HOGIFT";
        $MerchantRefID = $this->orderId;
        $TxnAmount = number_format((float)$this->order->getGrandTotal(), 2, '.', '');
        $ReturnURL = Mage::getUrl('amex/payment/response/');
        $action = "SaleTxn";

        $Invoice = "";
        $Invoice .= "<req>".
                        "<mer_id>" . $MerchantID . "</mer_id>".
                        "<mer_txn_id>" .$MerchantRefID. "</mer_txn_id>".
                        "<action>" . $action . "</action>".
                        "<txn_amt>" . $TxnAmount . "</txn_amt>".
                        "<cur>" . $currencyCode . "</cur>" .
                        "<lang>en</lang>";

        if($ReturnURL != "") {
           $Invoice .= "<ret_url>" . $ReturnURL . "</ret_url>"; 
        }
/*
        if($MerchantVar1 != "") {
            $Invoice .= "<mer_var1>" .$MerchantVar1. "</mer_var1>";
        }

        if($MerchantVar2 != "") {
            $Invoice .= "<mer_var2>" .$MerchantVar2. "</mer_var2>";
        }

        if($MerchantVar3 != "") {
            $Invoice .= "<mer_var3>" .$MerchantVar3. "</mer_var3>";
        }

        if($MerchantVar4 != "") {
            $Invoice .= "<mer_var4>" .$MerchantVar4. "</mer_var4>";
        }*/

        $Invoice .= "</req>";
        
        /**
        * Step 1 : Create the socket connection with IPG client
        */

            if ($IPGClientIP != "" && $IPGClientPort != "") 
            {
                $IPGSocket = fsockopen($IPGClientIP, $IPGClientPort, $ERRNO, $ERRSTR, $SOCKET_TIMEOUT);
            }
            else 
            {
                $error_message = "Could not establish a socket connection for given IPGClientIP = ". $IPGClientIP . "and IPGClientPort = ".$IPGClientPort; 
                $socket_creation_err = true;
            }

        /**
        * Step 2 : Send Invoice to IPG client 
        */

            if(!$socket_creation_err) 
            {
                socket_set_timeout($IPGSocket, $SOCKET_TIMEOUT);

                // Write the invoice to socket connection
                if(fwrite($IPGSocket,$Invoice) === false) 
                {
                    $error_message .= "Invoice could not be written to socket connection";
                    $invoice_sent_error = true;
                }
            }


        /**
        * Step 3 : Receive the encrypted Invoice from IPG client
        */

            if(!$socket_creation_err && !$invoice_sent_error)
            {
                while (!feof($IPGSocket)) 
                {
                    $EncryptedInvoice .= fread($IPGSocket, 8192);
                }    
             }

        /**
        * Step 4 : Close the socket connection
        */

            if(!$socket_creation_err) 
            {
                fclose($IPGSocket);
            }

        /**
        * Step 5 : Check for Encryption errors
        */

            if (!(strpos($EncryptedInvoice, '<error_code>') === false && strpos($EncryptedInvoice, '</error_code>') === false && strpos($EncryptedInvoice, '<error_msg>') === false && strpos($EncryptedInvoice, '</error_msg>') === false)) 
            {
                $encryption_ERR = true;

                $Error_code = substr($EncryptedInvoice, (strpos($EncryptedInvoice, '<error_code>')+12), (strpos($EncryptedInvoice, '</error_code>') - (strpos($EncryptedInvoice, '<error_code>')+12)));

                $Error_msg = substr($EncryptedInvoice, (strpos($EncryptedInvoice, '<error_msg>')+11), (strpos($EncryptedInvoice, '</error_msg>') - (strpos($EncryptedInvoice, '<error_msg>')+11)));

            }

        /**
        * Step 6 : Submit Encripted invoice to IPG server
        */

        ///include_once("invoice.inc");
    if(!$socket_creation_err && !$invoice_sent_error && !$encryption_ERR)
    {	
        ?>
        <html>
        <head>
        </head>

            <body onLoad="document.send_form.submit();">
                <form name="send_form" method="post" action="<?php echo $IPGServerURL?>" >
                    <input type="hidden" value="<?php echo $EncryptedInvoice?>" name="encryptedInvoicePay" />
                    <input type="submit" value="please click here if you are not redirected within a few seconds" />
                </form>
            </body>

        </html>
        <?php
    }
    else
    {
	// Following HTML code does not have to be available in the production code
	// here merchant can redirect to a error page
        // Eg : header('Location: http://www.example.com/error.php');
        ?>
        <html>
        <head>
        </head>

            <body>
                
                <h2>Error in generating Encrypted invoice</h2><br /><br />
                <h4>Socket Creation Errors</h4>
                <ul>
                    <li><b>Socket Error No : </b> <?php print $ERRNO ?></li>
                    <li><b>Socket Error String : </b><?php print $ERRSTR ?></li>
                    <li><b>Application Error Message : </b><?php print $error_message ?></li>
                </ul>

                <h4>Encryption Errors</h4>
                <ul>
                    <li><b>Error Code : </b> <?php print $Error_code ?></li>
                    <li><b>Error Message : </b><?php print $Error_msg ?></li>
                </ul>
                

            </body>

        </html>
        <?php
    }
        /*********************************************************************/
      
}
	
	
public function responseAction() {
	   
    /**
    * Initializing
    * IPG client IP, port and socket variables 
    */

    $IPGClientIP = "127.0.0.1";
    $IPGClientPort = "10000";

    $ERRNO = "";
    $ERRSTR = "";
    $SOCKET_TIMEOUT = 2;
    $IPGSocket = "";

    $EncryptedReceipt = "";
    $DecryptedReceipt = "";

    $error_message = "";
    $encrypted_rcpt_sent_error = "";
    $encryptedRcpt_ERR = "";
    $decryptedRcpt_ERR = "";


    $EncryptedReceipt = $_POST["encryptedReceiptPay"];

        if($EncryptedReceipt == "") {
            $error_message .= "Could not find Encrypted Receipt";
            $encryptedRcpt_ERR = true;
        }

    /**
    * Step 1 : Create the socket connection with IPG client
    */
        if(!$encryptedRcpt_ERR) {
            if ($IPGClientIP != "" && $IPGClientPort != "") {
                $IPGSocket = fsockopen($IPGClientIP, $IPGClientPort, $ERRNO, $ERRSTR, $SOCKET_TIMEOUT);
            } else {
                $error_message = "Could not establish a socket connection for given IPGClientIP = ". $IPGClientIP . "and IPGClientPort = ".$IPGClientPort; 
                $socket_creation_err = true;
            }      
        }

    /**
    * Step 2 : Send Encrypted Receipt to IPG client 
    */

        if(!$socket_creation_err && !$encryptedRcpt_ERR) {
            socket_set_timeout($IPGSocket, $SOCKET_TIMEOUT);

            // Write the encrypted receipt to socket connection
            if(fwrite($IPGSocket,$EncryptedReceipt) === false) {
                $error_message .= "Encrypted Receipt could not be written to socket connection";
                $encrypted_rcpt_sent_error = true;
            }
        }

    /**
    * Step 3 : Recieve the decrypted Receipt from IPG client
    */

        if(!$socket_creation_err && !$encrypted_rcpt_sent_error) {
            while (!feof($IPGSocket)) {
                $DecryptedReceipt .= fread($IPGSocket, 8192);
            }    
        }

    /**
    * Step 4 : Close the socket connection
    */
        if(!$socket_creation_err) {
            fclose($IPGSocket);
        }

    /**
    * Step 5 : Process $DecryptedReceipt
    */
    $Error_code = "";
    $Error_msg = "";
    $Acc_No = "";
    $Action = "";
    $Bank_ref_ID = "";
    $Currency = "";
    $IPG_txn_ID = "";
    $Lang = "";
    $Merchant_txn_ID = "";
    $Merchant_var1 = "";
    $Merchant_var2 = "";
    $Merchant_var3 = "";
    $Merchant_var4 = "";
    $Name = "";
    $Reason = "";
    $Transaction_amount = "";
    $Transaction_status = "";

        if (!(strpos($DecryptedReceipt, '<error_code>') === false && strpos($DecryptedReceipt, '</error_code>') === false && strpos($DecryptedReceipt, '<error_msg>') === false && strpos($DecryptedReceipt, '</error_msg>') === false)) {
            $decryptedRcpt_ERR = true;

            $Error_code = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<error_code>')+12), (strpos($DecryptedReceipt, '</error_code>') - (strpos($DecryptedReceipt, '<error_code>')+12)));

            $Error_msg = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<error_msg>')+11), (strpos($DecryptedReceipt, '</error_msg>') - (strpos($DecryptedReceipt, '<error_msg>')+11)));

        } else {

            if (!(strpos($DecryptedReceipt, '<acc_no>') === false && strpos($DecryptedReceipt, '</acc_no>') === false)) {
                $Acc_No = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<acc_no>')+8), (strpos($DecryptedReceipt, '</acc_no>') - (strpos($DecryptedReceipt, '<acc_no>')+8)));
            }

            if (!(strpos($DecryptedReceipt, '<action>') === false && strpos($DecryptedReceipt, '</action>') === false)) {
                $Action = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<action>')+8), (strpos($DecryptedReceipt, '</action>')-(strpos($DecryptedReceipt, '<action>')+8)));
            }

            if (!(strpos($DecryptedReceipt, '<bank_ref_id>') === false && strpos($DecryptedReceipt, '</bank_ref_id>') === false)) {
                $Bank_ref_ID = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<bank_ref_id>')+13), (strpos($DecryptedReceipt, '</bank_ref_id>')-(strpos($DecryptedReceipt, '<bank_ref_id>')+13)));
            }

            if (!(strpos($DecryptedReceipt, '<cur>') === false && strpos($DecryptedReceipt, '</cur>') === false)) {
                $Currency = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<cur>')+5),(strpos($DecryptedReceipt, '</cur>')-(strpos($DecryptedReceipt, '<cur>')+5)) );
            }

            if (!(strpos($DecryptedReceipt, '<ipg_txn_id>') === false && strpos($DecryptedReceipt, '</ipg_txn_id>') === false)) {
                $IPG_txn_ID = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<ipg_txn_id>')+12),(strpos($DecryptedReceipt, '</ipg_txn_id>')-(strpos($DecryptedReceipt, '<ipg_txn_id>')+12)) );
            }

            if (!(strpos($DecryptedReceipt, '<lang>') === false && strpos($DecryptedReceipt, '</lang>') === false)) {
                $Lang = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<lang>')+6),(strpos($DecryptedReceipt, '</lang>')-(strpos($DecryptedReceipt, '<lang>')+6)) );
            }

            if (!(strpos($DecryptedReceipt, '<mer_txn_id>') === false && strpos($DecryptedReceipt, '</mer_txn_id>') === false)) {
                $Merchant_txn_ID = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<mer_txn_id>')+12),(strpos($DecryptedReceipt, '</mer_txn_id>')-(strpos($DecryptedReceipt, '<mer_txn_id>')+12)) );
            }

            if (!(strpos($DecryptedReceipt, '<mer_var1>') === false && strpos($DecryptedReceipt, '</mer_var1>') === false)) {
                $Merchant_var1 = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<mer_var1>')+10),(strpos($DecryptedReceipt, '</mer_var1>')-(strpos($DecryptedReceipt, '<mer_var1>')+10)) );
            }

            if (!(strpos($DecryptedReceipt, '<mer_var2>') === false && strpos($DecryptedReceipt, '</mer_var2>') === false)) {
                $Merchant_var2 = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<mer_var2>')+10),(strpos($DecryptedReceipt, '</mer_var2>')-(strpos($DecryptedReceipt, '<mer_var2>')+10)) );
            }

            if (!(strpos($DecryptedReceipt, '<mer_var3>') === false && strpos($DecryptedReceipt, '</mer_var3>') === false)) {
                $Merchant_var3 = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<mer_var3>')+10),(strpos($DecryptedReceipt, '</mer_var3>')-(strpos($DecryptedReceipt, '<mer_var3>')+10)) );
            }

            if (!(strpos($DecryptedReceipt, '<mer_var4>') === false && strpos($DecryptedReceipt, '</mer_var4>') === false)) {
                $Merchant_var4 = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<mer_var4>')+10),(strpos($DecryptedReceipt, '</mer_var4>')-(strpos($DecryptedReceipt, '<mer_var4>')+10)) );
            }

            if (!(strpos($DecryptedReceipt, '<name>') === false && strpos($DecryptedReceipt, '</name>') === false)) {
                $Name = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<name>')+6),(strpos($DecryptedReceipt, '</name>')-(strpos($DecryptedReceipt, '<name>')+6)) );
            }

            if (!(strpos($DecryptedReceipt, '<reason>') === false && strpos($DecryptedReceipt, '</reason>') === false)) {
                $Reason = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<reason>')+8),(strpos($DecryptedReceipt, '</reason>')-(strpos($DecryptedReceipt, '<reason>')+8)) );
            }

            if (!(strpos($DecryptedReceipt, '<txn_amt>') === false && strpos($DecryptedReceipt, '</txn_amt>') === false)) {
                $Transaction_amount = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<txn_amt>')+9),(strpos($DecryptedReceipt, '</txn_amt>')-(strpos($DecryptedReceipt, '<txn_amt>')+9)) );
            }

            if (!(strpos($DecryptedReceipt, '<txn_status>') === false && strpos($DecryptedReceipt, '</txn_status>') === false)) {
                $Transaction_status = substr($DecryptedReceipt, (strpos($DecryptedReceipt, '<txn_status>')+12),(strpos($DecryptedReceipt, '</txn_status>')-(strpos($DecryptedReceipt, '<txn_status>')+12)) );
            }
        }


    /**
    * Step 6 : Finish Transaction
    */
        $orderId = $Merchant_txn_ID;
        
    if(!$socket_creation_err && !$encrypted_rcpt_sent_error && !$decryptedRcpt_ERR) {
        if($Transaction_status == "ACCEPTED") {
            
            // here merchant can redirect to a success page
            // Eg : header('Location: http://www.example.com/success.php');
            
            $order = Mage::getModel ('sales/order');
            $order->loadByIncrementId ( $orderId );
				
            $string = '';
            $string .= 'Txn Reference : '.$IPG_txn_ID.'<br/>';
            $string .= $Acc_No.'-(Amex)<br>';
                
            $order->setState( Mage_Sales_Model_Order::STATE_PROCESSING, true, 'Gateway has authorized the payment.<br/> Bank Reference ID : '.$Bank_ref_ID.'<br/>'.$string );
				
            $order->sendNewOrderEmail();
            $order->setEmailSent(true);
				
            $order->save ();
                
            Mage::getSingleton ('checkout/session')->unsQuoteId ();
            Mage_Core_Controller_Varien_Action::_redirect ('checkout/onepage/success', array ( '_secure' => true  ) );
            
            echo '<script>';
            echo 'window.location.replace("'.Mage::getUrl('checkout/onepage/success', array ( '_secure' => true  )).'");';
            echo '</script>';
            //die('ok');
        } else { //$Transaction_status == "REJECTED"
            // here merchant can redirect to a error page
            // Eg : header('Location: http://www.example.com/error.php');
            if($orderId){
					
            }else{
                $orderId = Mage::getSingleton ( 'checkout/session' )->getLastRealOrderId();
            }
				
            if (Mage::getSingleton ( 'checkout/session' )->getLastRealOrderId ()) {
                $order = Mage::getModel ( 'sales/order' )->loadByIncrementId ( $orderId );
                    if ($order->getId ()) {
                        $order->cancel ()->setState ( Mage_Sales_Model_Order::STATE_CANCELED, true, 'Gateway has declined the payment.'  )->save ();
                    }
            }
            Mage_Core_Controller_Varien_Action::_redirect ( 'checkout/onepage/failure', array ( '_secure' => true  ) );
            echo '<script>';
            echo 'window.location.replace("'.Mage::getUrl('checkout/onepage/failure', array ( '_secure' => true  )).'");';
            echo '</script>';
        }
    }
    
/***********************************************************************/
		
		
            Mage_Core_Controller_Varien_Action::_redirect ( '' );
            echo '<script>';
            echo 'window.location.replace("'.Mage::getUrl('').'");';
            echo '</script>';
	}
	
}

ob_end_flush(); 