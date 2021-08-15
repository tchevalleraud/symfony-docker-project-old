<?php
    namespace App\Tests\_extend;

    use Symfony\Component\Validator\ConstraintViolation;

    trait EntityTestCaseExtend {

        /**
         * @var \Doctrine\ORM\EntityManager
         */
        private $entityManager;

        protected function setUp(): void{
            $kernel = self::bootKernel();
            $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        }

        private function assertHasErrors($entity, int $number = 0){
            self::bootKernel();

            $errors     = self::$container->get('validator')->validate($entity);
            $messages   = [];

            /** @var ConstraintViolation $error */
            foreach ($errors as $error){
                $messages[] = $error->getPropertyPath(). " => " . $error->getMessage();
            }

            $this->assertCount($number, $errors, implode(", ", $messages));
        }

    }