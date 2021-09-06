<?php
    namespace App\Infrastructure\MessageHandler\Security;

    use App\Infrastructure\Message\Security\OTPCodeEmailMessage;
    use App\Infrastructure\Message\Security\OTPCodeSMSMessage;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
    use Symfony\Component\Mime\Email;
    use Twig\Environment;

    class OTPCodeSMSMessageHandler implements MessageHandlerInterface {

        private $smsSender;
        private $mailer;

        public function __construct($smsSender, MailerInterface $mailer){
            $this->smsSender = $smsSender;
            $this->mailer = $mailer;
        }

        public function __invoke(OTPCodeSMSMessage $message){
            $email = (new Email())
                ->from($this->smsSender."@mobile.local")
                ->to($message->getTo()."@mobile.local")
                ->subject("OTP Code")
                ->text("Your code is : ".$message->getOTPCode());
            $this->mailer->send($email);
        }

    }