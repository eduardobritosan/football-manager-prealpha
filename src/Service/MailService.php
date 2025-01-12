<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MailService implements NotificationStrategyInterface
{
    public function __construct(
        private readonly MailerInterface  $mailer
    ) {}

    /**
     * Sends a notification email about an employee's status change in a club.
     *
     * @param string $clubName The name of the club.
     * @param string $employeeName The name of the employee.
     * @param string $notifReason The reason for the notification ('sign' or other).
     *
     * @return void
     */
    public function notify(string $clubName, string $employeeName, string $notifReason)
    {
        $email = (new Email())
            ->from((new Address('MS_ZNjMFC@trial-pq3enl6zjj542vwr.mlsender.net', 'FM PreAlpha')))
            ->to('eebritosa@gmail.com');

        if ($notifReason == 'sign') {
            $email->text("Hi! $employeeName has signed with $clubName");
        } else {
            $email->text("Hi! $employeeName has been released from $clubName");
        }
        $this->mailer->send($email);
    }

    /**
     * Returns the type of message.
     *
     * @return string The type of message, which is 'email'.
     */
    public static function getMessageType(): string
    {
        return 'email';
    }
}
