<?php
    namespace App\Infrastructure\Message\Security;

    class OTPCodeEmailMessage {

        private $to;

        private $OTPCode;

        public function __construct($to, $OTPCode){
            $this->to       = $to;
            $this->OTPCode  = $OTPCode;
        }

        public function getTo() {
            return $this->to;
        }

        public function getOTPCode() {
            return $this->OTPCode;
        }

    }