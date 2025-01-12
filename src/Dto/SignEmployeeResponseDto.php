<?php

namespace App\Dto;

class SignEmployeeResponseDto
{
    private string $message;

    static function of(string $message,)
    {
        $dto = new SignEmployeeResponseDto();
        $dto->setMessage($message);
        return $dto;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }


    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    //TODO: Add status code to this to ease communication between repository and controller.
}
