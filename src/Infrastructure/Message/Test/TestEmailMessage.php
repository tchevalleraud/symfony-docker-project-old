<?php
    namespace App\Infrastructure\Message\Test;

    class TestEmailMessage {

        private $from;

        private $to;

        private $subject;

        private $content;

        public function __construct($to, $subject, $content, $from = "admin@pwsb.fr"){
            $this->to       = $to;
            $this->subject  = $subject;
            $this->content  = $content;
            $this->from     = $from;
        }

        public function getFrom() {
            return $this->from;
        }

        public function getTo() {
            return $this->to;
        }

        public function getSubject() {
            return $this->subject;
        }

        public function getContent() {
            return $this->content;
        }

    }