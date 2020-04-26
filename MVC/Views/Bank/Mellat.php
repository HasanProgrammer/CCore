@Extends('Layout')

@Start('Title', 'CCore-BankPage')

@Start('StyleSheet')

    <link rel="stylesheet" href="WWW/StyleSheet/Controllers/AdminController/Index.css">

@Close('StyleSheet')

@Start('Body')

    <form name="paymentform" id="paymentform" action="[@ config('BankPort')['Mellat']['BankPortURL'] @]" method="post">
        <input type="hidden" name="TerminalId"        value="[@ $terminalId     @]">
        <input type="hidden" name="UserName"          value="[@ $username       @]">
        <input type="hidden" name="UserPassword"      value="[@ $password       @]">
        <input type="hidden" name="PayDate"           value="[@ $paydate        @]">
        <input type="hidden" name="PayTime"           value="[@ $paytime        @]">
        <input type="hidden" name="PayAmount"         value="[@ $amount         @]">
        <input type="hidden" name="PayOrderId"        value="[@ $orderId        @]">
        <input type="hidden" name="PayAdditionalData" value="[@ $additionalData @]">
        <input type="hidden" name="PayCallBackUrl"    value="[@ $callBackUrl    @]">
        <input type="hidden" name="PayPayerId"        value="[@ $payerId        @]">
        <input type="hidden" name="RefId"             value="[@ $result         @]">
    </form>

@Close('Body')

@Start('JavaScript')

    <script type="text/javascript" src="WWW/JavaScript/Controllers/AdminController/Index.js"></script>
    <script type="text/javascript">
        document.getElementById("paymentform").submit();
    </script>

@Close('JavaScript')