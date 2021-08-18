<?php
    namespace App\UI\API\Docs;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;

    class RootController extends AbstractController {

        /**
         * @Route("/", name="index")
         */
        public function index(){
            throw new \LogicException('This method can be blank');
        }

    }