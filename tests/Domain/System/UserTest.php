<?php
    namespace App\Tests\Domain\System;

    use App\Domain\_mysql\System\Entity\User;
    use App\Tests\_extend\EntityTestCaseExtend;
    use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

    class UserTest extends KernelTestCase {

        use EntityTestCaseExtend;

        private function getEntity(): User {
            $user = (new User())
                ->setEmail("test@pwsb.fr")
                ->setPassword("ABCdef123")
                ->setApiToken("abcdef-123456-abcdef-123456-abcdef");
            return $user;
        }

        /**
         * API Token
         */

        public function test_APIToken_InvalidEntity(){
            $this->assertHasErrors($this->getEntity()->setApiToken("123456-123456-123456-123456"), 1);
            $this->assertHasErrors($this->getEntity()->setApiToken("12345-12345-12345-12345-12345"), 1);
        }

        public function test_APIToken_ValidEntity(){
            $this->assertEquals($this->getEntity()->getApiToken(), "abcdef-123456-abcdef-123456-abcdef");
            $this->assertHasErrors($this->getEntity()->setApiToken("123456-123456-123456-123456-123456"), 0);
            $this->assertHasErrors($this->getEntity()->setApiToken("abc123-abc123-abc123-abc123-abc123"), 0);
            $this->assertHasErrors($this->getEntity()->setApiToken("abc123-abc123-abc123-abc123-abc123"), 0);
        }

        /**
         * Email
         */

        public function test_Email_InvalidEntity_AlreadyUsed(){
            $this->assertHasErrors($this->getEntity()->setEmail("admin@pwsb.fr"));
        }

        public function test_Email_InvalidEntity_Length(){
            $this->assertHasErrors($this->getEntity()->setEmail("1@t.f"), 1);
            $this->assertHasErrors($this->getEntity()->setEmail("abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz0@pwsb.fr"), 1);
        }

        public function test_Email_InvalidEntity_NotBlank(){
            $this->assertHasErrors($this->getEntity()->setEmail(""), 2);
        }

        public function test_Email_ValidEntity_Email(){
            $this->assertEquals($this->getEntity()->getEmail(), "test@pwsb.fr");
            $this->assertHasErrors($this->getEntity()->setEmail("user1@pwsb.fr"), 0);
            $this->assertHasErrors($this->getEntity()->setEmail("user-1@pwsb.fr"), 0);
            $this->assertHasErrors($this->getEntity()->setEmail("user1@weasyb.com"), 0);
            $this->assertHasErrors($this->getEntity()->setEmail("lastname.firstname@weasyb.com"), 0);
        }

        /**
         * Entity
         */
        public function test_EntityIsValid(){
            $this->assertHasErrors($this->getEntity(), 0);
        }

    }