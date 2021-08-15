<?php
    namespace App\Application\Command\System;

    use Proxies\__CG__\App\Domain\_mysql\System\Entity\User;
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
        private $em;

        public function __construct(string $name = null, ContainerInterface $container, KernelInterface $kernel, UserPasswordEncoderInterface $passwordEncoder) {
            parent::__construct($name);

            $this->container = $container;
            $this->kernel = $kernel;
            $this->passwordEncoder = $passwordEncoder;

            $this->em = $this->container->get('doctrine')->getManager('mysql');
        }

        public function execute(InputInterface $input, OutputInterface $output) {
            $user = $this->em->getRepository(User::class)->findOneBy(['email' => 'admin@pwsb.fr']);
            if(!$user){
                $user = new User();
                $user->setEmail("admin@pwsb.fr");
                $user->setPassword($this->passwordEncoder->encodePassword($user, "password"));
                $user->setApiToken(implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6)));

                $this->em->persist($user);
                $this->em->flush();
            }

            return Command::SUCCESS;
        }

    }