<?php

namespace App\Dto;

class BudgetUpdateDto
{
    private string $budget;
    private string $clubId;


    static function of(string $budget, string $clubId,)
    {
        $dto = new BudgetUpdateDto();
        $dto->setBudget($budget)->setClubId($clubId);
        return $dto;
    }

    /**
     * @param string $budget
     */
    public function setBudget(string $budget): self
    {
        $this->budget = $budget;
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
     * @return string
     */
    public function getBudget(): string
    {
        return $this->budget;
    }

    /**
     * @return string
     */
    public function getClubId(): string
    {
        return $this->clubId;
    }
}
