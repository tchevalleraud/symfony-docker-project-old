<?php
    namespace App\Infrastructure\MessageHandler\Test;

    use App\Infrastructure\Message\Test\TestEmailMessage;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
    use Symfony\Component\Mime\Email;

    class TestEmailMessageHandler implements MessageHandlerInterface {

        private $noReplyEmail;
        private $mailer;

        public function __construct($noReplyEmail, MailerInterface $mailer){
            $this->noReplyEmail = $noReplyEmail;
            $this->mailer = $mailer;
        }

        public function __invoke(TestEmailMessage $message){
            $email = (new Email())
                ->from($this->noReplyEmail)
                ->to($message->getTo())
                ->subject($message->getSubject())
                ->text($message->getContent());
            $this->mailer->send($email);
        }

    }