<?php

namespace App\Tests\Service;

use App\Service\NotificationService;
use PHPUnit\Framework\TestCase;
use App\Service\NotificationStrategyInterface;

class NotificationServiceTest extends TestCase
{
    public function testNotifyWithValidStrategy()
    {
        $strategyMock = $this->createMock(NotificationStrategyInterface::class);
        $strategyMock->expects($this->once())
            ->method('notify')
            ->with('ClubName', 'EmployeeName', 'NotifReason');

        $strategies = ['email' => $strategyMock];
        $notificationService = new NotificationService($strategies);

        $notificationService->notify('ClubName', 'EmployeeName', 'NotifReason', 'email');
    }

    public function testNotifyWithInvalidStrategy()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unhandled extension');

        $strategies = [];
        $notificationService = new NotificationService($strategies);

        $notificationService->notify('ClubName', 'EmployeeName', 'NotifReason', 'invalidType');
    }
}
