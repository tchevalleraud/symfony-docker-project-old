<?php
    namespace App\Application\Services;

    use App\Domain\_mysql\System\Entity\User;
    use App\Infrastructure\Message\Security\OTPCodeEmailMessage;
    use App\Infrastructure\Message\Security\OTPCodeSMSMessage;
    use Doctrine\ORM\EntityManagerInterface;
    use Otp\Otp;
    use ParagonIE\ConstantTime\Encoding;
    use Symfony\Component\HttpFoundation\Session\Session;
    use Symfony\Component\Messenger\MessageBusInterface;

    class OTPService {

        private $em;
        private $session;

        public function __construct(EntityManagerInterface $em, Session $session) {
            $this->em           = $em;
            $this->session      = $session;
        }

        public function getOTPVerify($otp_method, User $user, MessageBusInterface $messageBus){
            if($otp_method == "email"){
                $user->setOtpCode(rand(100000, 999999));
                $this->em->persist($user);
                $this->em->flush();

                $messageBus->dispatch(new OTPCodeEmailMessage($user->getEmail(), $user->getOtpCode()));
            } elseif($otp_method == "sms"){
                $user->setOtpCode(rand(100000, 999999));
                $this->em->persist($user);
                $this->em->flush();

                $messageBus->dispatch(new OTPCodeSMSMessage($user->getPhone(), $user->getOtpCode()));
            } elseif($otp_method == "google") {
                return true;
            } else {
                $this->session->set('2fa-verified', true);
            }
        }

        public function verifyOTP($otp_method, User $user, $token){
            if($otp_method == "email"){
                if($token == $user->getOtpCode()){
                    $user->setOtpCode(null);
                    $this->em->persist($user);
                    $this->em->flush();
                    return true;
                }
            } elseif($otp_method == "sms"){
                if($token == $user->getOtpCode()){
                    $user->setOtpCode(null);
                    $this->em->persist($user);
                    $this->em->flush();
                    return true;
                }
            } elseif($otp_method == "google"){
                $otp = new Otp();
                if($otp->checkTotp(Encoding::base32DecodeUpper($user->getOtpCodeSecret()), $token)){
                    return true;
                }
            }
            throw new \Exception("OTP is not valid");
        }

    }