<?php
    namespace App\Tests\Domain\System;

    use App\Domain\_mysql\System\Entity\User;
    use App\Tests\_extend\EntityTestCaseExtend;
    use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

    class UserTest extends KernelTestCase {

        use EntityTestCaseExtend;

        private function getEntity(): User {
            $user = (new User())
                ->setEmail("admin@example.com")
                ->setPassword("ABCdef123")
                ->setApiToken("abcdef-123456-abcdef-123456-abcdef");
            return $user;
        }

        public function test_ValidEntity(){
            $this->assertHasErrors($this->getEntity(), 0);
        }

    }