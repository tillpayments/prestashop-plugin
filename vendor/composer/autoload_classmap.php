<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'TillPayments\\Client\\Callback\\ChargebackData' => $baseDir . '/client/Callback/ChargebackData.php',
    'TillPayments\\Client\\Callback\\ChargebackReversalData' => $baseDir . '/client/Callback/ChargebackReversalData.php',
    'TillPayments\\Client\\Callback\\Result' => $baseDir . '/client/Callback/Result.php',
    'TillPayments\\Client\\Client' => $baseDir . '/client/Client.php',
    'TillPayments\\Client\\CustomerProfile\\CustomerData' => $baseDir . '/client/CustomerProfile/CustomerData.php',
    'TillPayments\\Client\\CustomerProfile\\DeleteProfileResponse' => $baseDir . '/client/CustomerProfile/DeleteProfileResponse.php',
    'TillPayments\\Client\\CustomerProfile\\GetProfileResponse' => $baseDir . '/client/CustomerProfile/GetProfileResponse.php',
    'TillPayments\\Client\\CustomerProfile\\PaymentData\\CardData' => $baseDir . '/client/CustomerProfile/PaymentData/CardData.php',
    'TillPayments\\Client\\CustomerProfile\\PaymentData\\IbanData' => $baseDir . '/client/CustomerProfile/PaymentData/IbanData.php',
    'TillPayments\\Client\\CustomerProfile\\PaymentData\\PaymentData' => $baseDir . '/client/CustomerProfile/PaymentData/PaymentData.php',
    'TillPayments\\Client\\CustomerProfile\\PaymentData\\WalletData' => $baseDir . '/client/CustomerProfile/PaymentData/WalletData.php',
    'TillPayments\\Client\\CustomerProfile\\PaymentInstrument' => $baseDir . '/client/CustomerProfile/PaymentInstrument.php',
    'TillPayments\\Client\\CustomerProfile\\UpdateProfileResponse' => $baseDir . '/client/CustomerProfile/UpdateProfileResponse.php',
    'TillPayments\\Client\\Data\\CreditCardCustomer' => $baseDir . '/client/Data/CreditCardCustomer.php',
    'TillPayments\\Client\\Data\\Customer' => $baseDir . '/client/Data/Customer.php',
    'TillPayments\\Client\\Data\\Data' => $baseDir . '/client/Data/Data.php',
    'TillPayments\\Client\\Data\\IbanCustomer' => $baseDir . '/client/Data/IbanCustomer.php',
    'TillPayments\\Client\\Data\\Item' => $baseDir . '/client/Data/Item.php',
    'TillPayments\\Client\\Data\\Request' => $baseDir . '/client/Data/Request.php',
    'TillPayments\\Client\\Data\\Result\\CreditcardData' => $baseDir . '/client/Data/Result/CreditcardData.php',
    'TillPayments\\Client\\Data\\Result\\IbanData' => $baseDir . '/client/Data/Result/IbanData.php',
    'TillPayments\\Client\\Data\\Result\\PhoneData' => $baseDir . '/client/Data/Result/PhoneData.php',
    'TillPayments\\Client\\Data\\Result\\ResultData' => $baseDir . '/client/Data/Result/ResultData.php',
    'TillPayments\\Client\\Data\\Result\\WalletData' => $baseDir . '/client/Data/Result/WalletData.php',
    'TillPayments\\Client\\Exception\\ClientException' => $baseDir . '/client/Exception/ClientException.php',
    'TillPayments\\Client\\Exception\\InvalidValueException' => $baseDir . '/client/Exception/InvalidValueException.php',
    'TillPayments\\Client\\Exception\\RateLimitException' => $baseDir . '/client/Exception/RateLimitException.php',
    'TillPayments\\Client\\Exception\\TimeoutException' => $baseDir . '/client/Exception/TimeoutException.php',
    'TillPayments\\Client\\Exception\\TypeException' => $baseDir . '/client/Exception/TypeException.php',
    'TillPayments\\Client\\Http\\ClientInterface' => $baseDir . '/client/Http/ClientInterface.php',
    'TillPayments\\Client\\Http\\CurlClient' => $baseDir . '/client/Http/CurlClient.php',
    'TillPayments\\Client\\Http\\CurlExec' => $baseDir . '/client/Http/CurlExec.php',
    'TillPayments\\Client\\Http\\Exception\\ClientException' => $baseDir . '/client/Http/Exception/ClientException.php',
    'TillPayments\\Client\\Http\\Exception\\ResponseException' => $baseDir . '/client/Http/Exception/ResponseException.php',
    'TillPayments\\Client\\Http\\Response' => $baseDir . '/client/Http/Response.php',
    'TillPayments\\Client\\Http\\ResponseInterface' => $baseDir . '/client/Http/ResponseInterface.php',
    'TillPayments\\Client\\Json\\DataObject' => $baseDir . '/client/Json/DataObject.php',
    'TillPayments\\Client\\Json\\ErrorResponse' => $baseDir . '/client/Json/ErrorResponse.php',
    'TillPayments\\Client\\Json\\ResponseObject' => $baseDir . '/client/Json/ResponseObject.php',
    'TillPayments\\Client\\Schedule\\ScheduleData' => $baseDir . '/client/Schedule/ScheduleData.php',
    'TillPayments\\Client\\Schedule\\ScheduleError' => $baseDir . '/client/Schedule/ScheduleError.php',
    'TillPayments\\Client\\Schedule\\ScheduleResult' => $baseDir . '/client/Schedule/ScheduleResult.php',
    'TillPayments\\Client\\StatusApi\\StatusRequestData' => $baseDir . '/client/StatusApi/StatusRequestData.php',
    'TillPayments\\Client\\StatusApi\\StatusResult' => $baseDir . '/client/StatusApi/StatusResult.php',
    'TillPayments\\Client\\Transaction\\Base\\AbstractTransaction' => $baseDir . '/client/Transaction/Base/AbstractTransaction.php',
    'TillPayments\\Client\\Transaction\\Base\\AbstractTransactionWithReference' => $baseDir . '/client/Transaction/Base/AbstractTransactionWithReference.php',
    'TillPayments\\Client\\Transaction\\Base\\AddToCustomerProfileInterface' => $baseDir . '/client/Transaction/Base/AddToCustomerProfileInterface.php',
    'TillPayments\\Client\\Transaction\\Base\\AddToCustomerProfileTrait' => $baseDir . '/client/Transaction/Base/AddToCustomerProfileTrait.php',
    'TillPayments\\Client\\Transaction\\Base\\AmountableInterface' => $baseDir . '/client/Transaction/Base/AmountableInterface.php',
    'TillPayments\\Client\\Transaction\\Base\\AmountableTrait' => $baseDir . '/client/Transaction/Base/AmountableTrait.php',
    'TillPayments\\Client\\Transaction\\Base\\ItemsInterface' => $baseDir . '/client/Transaction/Base/ItemsInterface.php',
    'TillPayments\\Client\\Transaction\\Base\\ItemsTrait' => $baseDir . '/client/Transaction/Base/ItemsTrait.php',
    'TillPayments\\Client\\Transaction\\Base\\OffsiteInterface' => $baseDir . '/client/Transaction/Base/OffsiteInterface.php',
    'TillPayments\\Client\\Transaction\\Base\\OffsiteTrait' => $baseDir . '/client/Transaction/Base/OffsiteTrait.php',
    'TillPayments\\Client\\Transaction\\Base\\ScheduleInterface' => $baseDir . '/client/Transaction/Base/ScheduleInterface.php',
    'TillPayments\\Client\\Transaction\\Base\\ScheduleTrait' => $baseDir . '/client/Transaction/Base/ScheduleTrait.php',
    'TillPayments\\Client\\Transaction\\Capture' => $baseDir . '/client/Transaction/Capture.php',
    'TillPayments\\Client\\Transaction\\Debit' => $baseDir . '/client/Transaction/Debit.php',
    'TillPayments\\Client\\Transaction\\Deregister' => $baseDir . '/client/Transaction/Deregister.php',
    'TillPayments\\Client\\Transaction\\Error' => $baseDir . '/client/Transaction/Error.php',
    'TillPayments\\Client\\Transaction\\Payout' => $baseDir . '/client/Transaction/Payout.php',
    'TillPayments\\Client\\Transaction\\Preauthorize' => $baseDir . '/client/Transaction/Preauthorize.php',
    'TillPayments\\Client\\Transaction\\Refund' => $baseDir . '/client/Transaction/Refund.php',
    'TillPayments\\Client\\Transaction\\Register' => $baseDir . '/client/Transaction/Register.php',
    'TillPayments\\Client\\Transaction\\Result' => $baseDir . '/client/Transaction/Result.php',
    'TillPayments\\Client\\Transaction\\VoidTransaction' => $baseDir . '/client/Transaction/VoidTransaction.php',
    'TillPayments\\Client\\Xml\\Generator' => $baseDir . '/client/Xml/Generator.php',
    'TillPayments\\Client\\Xml\\Parser' => $baseDir . '/client/Xml/Parser.php',
    'TillPayments\\Prestashop\\PaymentMethod\\CreditCard' => $baseDir . '/payment_method/CreditCard.php',
    'TillPayments\\Prestashop\\PaymentMethod\\PaymentMethodInterface' => $baseDir . '/payment_method/PaymentMethodInterface.php',
);
