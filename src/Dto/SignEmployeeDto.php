<?php

namespace App\Dto;

class SignEmployeeDto
{
    private string $playerNif;
    private string $clubId;
    private string $salary;

    static function of(string $playerNif, string $clubId, string $salary)
    {
        $dto = new SignEmployeeDto();
        $dto->setPlayerNif($playerNif)->setClubId($clubId)
            ->setSalary($salary);
        return $dto;
    }

    /**
     * @param string $playerNif
     */
    public function setPlayerNif(string $playerNif): self
    {
        $this->playerNif = $playerNif;
        return $this;
    }

    /**
     * @param string $clubId
     */
    public function setClubId(string $clubId): self
    {
        $this->clubId = $clubId;
        return $this;
    }

    /**
     * @param string $clubId
     */
    public function setSalary(string $salary): self
    {
        $this->salary = $salary;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlayerNif(): string
    {
        return $this->playerNif;
    }

    /**
     * @return string
     */
    public function getClubId(): string
    {
        return $this->clubId;
    }

    /**
     * @return string
     */
    public function getSalary(): float
    {
        return (float)$this->salary;
    }
}
