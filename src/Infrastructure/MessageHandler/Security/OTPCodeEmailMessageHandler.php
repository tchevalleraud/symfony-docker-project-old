<?php
    namespace App\Infrastructure\MessageHandler\Security;

    use App\Infrastructure\Message\Security\OTPCodeEmailMessage;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
    use Symfony\Component\Mime\Email;
    use Twig\Environment;

    class OTPCodeEmailMessageHandler implements MessageHandlerInterface {

        private $twig;
        private $noReplyEmail;
        private $mailer;

        public function __construct(Environment $twig, $noReplyEmail, MailerInterface $mailer){
            $this->twig = $twig;
            $this->noReplyEmail = $noReplyEmail;
            $this->mailer = $mailer;
        }

        public function __invoke(OTPCodeEmailMessage $message){
            $email = (new Email())
                ->from($this->noReplyEmail)
                ->to($message->getTo())
                ->subject("OTP Code")
                ->text("Your code is : ".$message->getOTPCode());
            $this->mailer->send($email);
        }

    }