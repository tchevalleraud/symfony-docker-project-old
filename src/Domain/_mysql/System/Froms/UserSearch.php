<?php
    namespace App\Domain\_mysql\System\Froms;

    class UserSearch {

        private $user;

        private $lastname;

        private $firstname;

        private $sort;

        private $order;

        private $limit;

        public function __construct(){
            $this->sort     = 'u.email';
            $this->order    = 'asc';
            $this->limit    = 10;
        }

        public function getUser() {
            return $this->user;
        }

        public function setUser($user): self {
            $this->user = $user;
            return $this;
        }

        public function getLastname() {
            return $this->lastname;
        }

        public function setLastname($lastname): self {
            $this->lastname = $lastname;
            return $this;
        }

        public function getFirstname() {
            return $this->firstname;
        }

        public function setFirstname($firstname): self {
            $this->firstname = $firstname;
            return $this;
        }

        public function getSort(): string {
            return $this->sort;
        }

        public function setSort(string $sort): self {
            $this->sort = $sort;
            return $this;
        }

        public function getOrder(): string {
            return $this->order;
        }

        public function setOrder(string $order): self {
            $this->order = $order;
            return $this;
        }

        public function getLimit(): int {
            return $this->limit;
        }

        public function setLimit(int $limit): self {
            $this->limit = $limit;
            return $this;
        }

        /***************************************************************************************************************
         * CUSTOM FUNCTION
         */
        public function getSearchParam(){
            $count = 0;
            if(!empty($this->getUser())) $count++;
            return $count;
        }

        public function getSearchFilter(){
            $count = 0;
            if(!empty($this->getLastname())) $count++;
            if(!empty($this->getFirstname())) $count++;
            return $count;
        }

    }