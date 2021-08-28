<?php
    namespace App\Application\Command\System;

    use App\Application\Services\AWSS3Service;
    use App\Domain\_mysql\System\Entity\User;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Symfony\Component\HttpKernel\KernelInterface;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

    class InitCommand extends Command {

        protected static $defaultName = "app:system:init";

        private $container;
        private $kernel;
        private $passwordEncoder;
        private $AWSS3Service;
        private $em;

        public function __construct(string $name = null, ContainerInterface $container, KernelInterface $kernel, UserPasswordEncoderInterface $passwordEncoder, AWSS3Service $AWSS3Service) {
            parent::__construct($name);

            $this->container = $container;
            $this->kernel = $kernel;
            $this->passwordEncoder = $passwordEncoder;
            $this->AWSS3Service = $AWSS3Service;

            $this->em = $this->container->get('doctrine')->getManager('mysql');
        }

        public function execute(InputInterface $input, OutputInterface $output) {
            $user = $this->em->getRepository(User::class)->findOneBy(['email' => 'admin@pwsb.fr']);
            if(!$user){
                $user = new User();
                $user->setFirstname("administrator");
                $user->setLastname("system");
                $user->setJobTitle("IT Bot");
                $user->setEmail("admin@pwsb.fr");
                $user->setPassword($this->passwordEncoder->encodePassword($user, "password"));
                $user->setApiToken(implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6)));
            } else {
                $user->setFirstname("administrator");
                $user->setLastname("system");
                $user->setEmail("admin@pwsb.fr");
            }
            $this->em->persist($user);

            if(!$this->AWSS3Service->isExistBucket('user')) $this->AWSS3Service->createBucket('user');

            $this->em->flush();

            return Command::SUCCESS;
        }

    }