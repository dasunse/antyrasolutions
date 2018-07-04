<?php include '../au.com.gateway.client/GatewayClient.php'; ?>
<?php include '../au.com.gateway.client.config/ClientConfig.php'; ?>
<?php include '../au.com.gateway.client.component/RequestHeader.php'; ?>
<?php include '../au.com.gateway.client.component/CreditCard.php'; ?>
<?php include '../au.com.gateway.client.component/TransactionAmount.php'; ?>
<?php include '../au.com.gateway.client.component/Redirect.php'; ?>
<?php include '../au.com.gateway.client.facade/BaseFacade.php'; ?>
<?php include '../au.com.gateway.client.facade/Payment.php'; ?>
<?php include '../au.com.gateway.client.payment/PaymentInitRequest.php'; ?>
<?php include '../au.com.gateway.client.payment/PaymentInitResponse.php'; ?>
<?php include '../au.com.gateway.client.root/PaycorpRequest.php'; ?>
<?php include '../au.com.gateway.client.utils/IJsonHelper.php'; ?>
<?php include '../au.com.gateway.client.helpers/PaymentInitJsonHelper.php'; ?>
<?php include '../au.com.gateway.client.utils/HmacUtils.php'; ?>
<?php include '../au.com.gateway.client.utils/CommonUtils.php'; ?>
<?php include '../au.com.gateway.client.utils/RestClient.php'; ?>
<?php include '../au.com.gateway.client.enums/TransactionType.php'; ?>
<?php include '../au.com.gateway.client.enums/Version.php'; ?>
<?php include '../au.com.gateway.client.enums/Operation.php'; ?>
<?php include '../au.com.gateway.client.facade/Vault.php'; ?>
<?php include '../au.com.gateway.client.facade/Report.php'; ?>
<?php include '../au.com.gateway.client.facade/AmexWallet.php'; ?>

<?php

date_default_timezone_set('Asia/Colombo');
error_reporting(E_ALL);
ini_set('display_errors', 1);
/*------------------------------------------------------------------------------
STEP1: Build ClientConfig object
------------------------------------------------------------------------------*/
$ClientConfig = new ClientConfig();
$ClientConfig->setServiceEndpoint("https://sampath.paycorp.com.au/rest/service/proxy");
$ClientConfig->setAuthToken("2d6f8fca-9e24-424d-8ebf-e295b3ce0be8");
$ClientConfig->setHmacSecret("mn2TXNl2TH2hAOqh");
$ClientConfig->setValidateOnly(FALSE);
/*------------------------------------------------------------------------------
STEP2: Build Client object
------------------------------------------------------------------------------*/
$Client = new GatewayClient($ClientConfig);
/*------------------------------------------------------------------------------
STEP3: Build PaymentInitRequest object
------------------------------------------------------------------------------*/
$initRequest = new PaymentInitRequest();
$initRequest->setClientId(14000148);
$initRequest->setTransactionType(TransactionType::$PURCHASE);
$initRequest->setClientRef("12584");
$initRequest->setComment("ghfhfh");
$initRequest->setTokenize(TRUE);
$initRequest->setExtraData(array("ADD-KEY-1" => "ADD-VALUE-1", "ADD-KEY-2" => "ADD-VALUE-2"));
// sets transaction-amounts details (all amounts are in cents)
$transactionAmount = new TransactionAmount();
$transactionAmount->setTotalAmount(0);
$transactionAmount->setServiceFeeAmount(0);
$transactionAmount->setPaymentAmount(1010);
$transactionAmount->setCurrency("USD");
$initRequest->setTransactionAmount($transactionAmount);
// sets redirect settings
$redirect = new Redirect();
$redirect->setReturnUrl("http://localhost/sampath_paymet/au.com.gateway.IT/pcw_payment-complete_UT.php");
$redirect->setReturnMethod("GET");
$initRequest->setRedirect($redirect);

/*------------------------------------------------------------------------------
STEP4: Process PaymentInitRequest object
------------------------------------------------------------------------------*/
$initResponse = $Client->payment()->init($initRequest);

/*------------------------------------------------------------------------------
STEP5: Extract PaymentInitResponse object
------------------------------------------------------------------------------*/
echo '<br><br>PCW Payment-Init Respopnse: --------------------------------------';
echo '<br>Req Id : ' . $initResponse->getReqid();
echo '<br>Payment Page Url : ' . $initResponse->getPaymentPageUrl();
echo '<br>Expire At : ' . $initResponse->getExpireAt();
echo '<br>------------------------------------------------------------------<br>';
?>

<html>
    <head></head>
    <body>
        <div style="float:left">
            <br><br><br><br>
            <iframe class="col-lg-12"  height="600px" width="600px" src="<?php echo $initResponse->getPaymentPageUrl(); ?>">
        </div>
    </body>
</html>
