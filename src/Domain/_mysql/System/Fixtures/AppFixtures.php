<?php
    namespace App\Domain\_mysql\System\Fixtures;

    use App\Domain\_mysql\System\Entity\App;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Persistence\ObjectManager;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

    class AppFixtures extends Fixture {

        private $passwordHasher;

        public function __construct(UserPasswordHasherInterface $passwordHasher){
            $this->passwordHasher = $passwordHasher;
        }

        public function load(ObjectManager $manager){
            for($i = 1; $i <= 10; $i++){
                $manager->persist($this->newApp("app-".$i));
            }
            $manager->flush();
        }

        private function newApp($name): App {
            $app = new App();
            $app->setEmail($name."@pwsb.fr");
            $app->setName($name);
            $app->setPassword($this->passwordHasher->hashPassword($app, "password"));
            $app->setApiToken(implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 3)));
            return $app;
        }

    }