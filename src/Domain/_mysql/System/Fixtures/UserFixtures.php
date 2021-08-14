<?php
    namespace App\Domain\_mysql\System\Fixtures;

    use App\Domain\_mysql\System\Entity\User;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Persistence\ObjectManager;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

    class UserFixtures extends Fixture {

        private $passwordHasher;

        public function __construct(UserPasswordHasherInterface $passwordHasher){
            $this->passwordHasher = $passwordHasher;
        }

        public function load(ObjectManager $manager){
            $manager->persist($this->newUser("thibault.chevalleraud@gmail.com"));
            for($i = 1; $i <= 10; $i++){
                $manager->persist($this->newUser("user".$i."@test.com"));
            }
            $manager->flush();
        }

        private function newUser($email, $password = "password"): User {
            $user = new User();
            $user->setEmail($email);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->setApiToken(implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6)));

            return $user;
        }

    }