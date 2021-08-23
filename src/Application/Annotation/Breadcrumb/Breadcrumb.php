<?php
    namespace App\Application\Annotation\Breadcrumb;

    /**
     * @Annotation
     */
    class Breadcrumb {

        public $key;
        public $label;
        public $route;
        public $params = [];
        public $parent = null;

    }