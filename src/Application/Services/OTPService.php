<?php
    namespace App\Application\Services;

    use App\Domain\_mysql\System\Entity\User;
    use App\Infrastructure\Message\Security\OTPCodeEmailMessage;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Session\Session;
    use Symfony\Component\Messenger\MessageBusInterface;

    class OTPService {

        private $em;
        private $session;

        public function __construct(EntityManagerInterface $em, Session $session) {
            $this->em           = $em;
            $this->session      = $session;
        }

        public function getOTPVerify(User $user, MessageBusInterface $messageBus){
            if($user->getOtp() == "email"){
                $user->setOtpCode(rand(100000, 999999));
                $this->em->persist($user);
                $this->em->flush();

                $messageBus->dispatch(new OTPCodeEmailMessage($user->getEmail(), $user->getOtpCode()));
            } elseif ($user->getOpt() == "google"){
                dd("Nothing");
            } else {
                $this->session->set('2fa-verified', true);
            }
        }

        public function verifyOTP(User $user, $token){
            if($user->getOtp() == "email"){
                if($token == $user->getOtpCode()){
                    $user->setOtpCode(null);
                    $this->em->persist($user);
                    $this->em->flush();
                    return true;
                }
            }
            throw new \Exception("OTP is not valid");
        }

    }