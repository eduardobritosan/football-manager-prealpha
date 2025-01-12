<?php

namespace App\Service;

/**
 * Class NotificationService
 *
 * This service is responsible for handling notifications within the application.
 * It provides methods to send notifications for users.
 *
 * @package App\Service
 */
class NotificationService
{
    private array $strategies;

    public function __construct(iterable $strategies)
    {
        $this->strategies = $strategies instanceof \Traversable ? iterator_to_array($strategies) : $strategies;
    }

    /**
     * Notify the specified employee of the given club with a message of the specified type.
     *
     * @param string $clubName The name of the club.
     * @param string $employeeName The name of the employee to notify.
     * @param string $notifReason The reason for the notification.
     * @param string $messageType The type of message to send.
     *
     * @throws \Exception If the message type is not handled by any strategy.
     */
    public function notify(string $clubName, string $employeeName, string $notifReason, string $messageType)
    {
        if (!isset($this->strategies[$messageType])) {
            throw new \Exception('Unhandled extension');
        }

        $strategy = $this->strategies[$messageType];
        $strategy->notify($clubName, $employeeName, $notifReason);
    }
}
