<?php

namespace App\Tests\Service;

use App\Service\MailService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailServiceTest extends TestCase
{
    private $mailer;
    private $mailService;

    protected function setUp(): void
    {
        $this->mailer = $this->getMockBuilder(MailerInterface::class)->getMock();
        $this->mailService = new MailService($this->mailer instanceof MailerInterface ? $this->mailer : null);
    }

    public function testNotifySign()
    {
        $this->mailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function (Email $email) {
                return $email->getTo()[0]->getAddress() === 'eebritosa@gmail.com' &&
                    $email->getTextBody() === 'Hi! John Doe has signed with FC Awesome';
            }));

        $this->mailService->notify('FC Awesome', 'John Doe', 'sign');
    }

    public function testNotifyRelease()
    {
        $this->mailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function (Email $email) {
                return $email->getTo()[0]->getAddress() === 'eebritosa@gmail.com' &&
                    $email->getTextBody() === 'Hi! John Doe has been released from FC Awesome';
            }));

        $this->mailService->notify('FC Awesome', 'John Doe', 'release');
    }

    public function testGetMessageType()
    {
        $this->assertEquals('email', MailService::getMessageType());
    }
}
