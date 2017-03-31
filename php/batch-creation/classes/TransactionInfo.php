<?php
namespace Linkfire;
class TransactionInfo {
    public $TransactionId;

    function __construct($transactionId) {
        $this->TransactionId = $transactionId;
    }
}