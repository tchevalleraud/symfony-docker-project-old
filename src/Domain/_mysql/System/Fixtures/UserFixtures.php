<?php
    namespace App\Domain\_mysql\System\Fixtures;

    use App\Domain\_mysql\System\Entity\User;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Persistence\ObjectManager;
    use Faker\Factory;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

    class UserFixtures extends Fixture {

        private $passwordHasher;

        public function __construct(UserPasswordHasherInterface $passwordHasher){
            $this->passwordHasher = $passwordHasher;
        }

        public function load(ObjectManager $manager){
            $manager->persist($this->newUser("admin@pwsb.fr"));
            for($i = 1; $i <= 100; $i++){
                $manager->persist($this->newFakeUser("password"));
            }
            $manager->flush();
        }

        private function newUser($email, $password = "password", $lastname = "system", $firstname = "administrator"): User {
            $user = new User();
            $user->setLastname($lastname);
            $user->setFirstname($firstname);
            $user->setJobTitle("default job");
            $user->setEmail($email);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->setApiToken(implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6)));

            return $user;
        }

        private function newFakeUser($password = "password"): User {
            $faker = Factory::create('fr_FR');
            $gender = ['male', 'female'];
            $person = ['gender'    => $gender[rand(0,1)]];

            $person['lastname'] = $faker->lastName($person['gender']);
            $person['firstname'] = $faker->firstName($person['gender']);
            $person['email'] = $person['firstname'].".".$person['lastname']."@".$faker->freeEmailDomain();

            $user = new User();
            $user->setAvatar("https://randomuser.me/api/portraits/".($person['gender'] == "male" ? "men" : "women")."/".rand(1, 60).".jpg");
            $user->setLastname($person['lastname']);
            $user->setFirstname($person['firstname']);
            $user->setJobTitle("default job");
            $user->setEmail($person['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->setApiToken(implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6)));

            return $user;
        }

    }