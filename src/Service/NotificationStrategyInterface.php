<?php

namespace App\Service;

interface NotificationStrategyInterface
{
    public function notify(string $clubName, string $employeeName, string $notifReason);
    public static function getMessageType(): string;
}
