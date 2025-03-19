<?php

declare(strict_types=1);


namespace App\Application\Command\WorkEntry;

use DateTimeImmutable;

class UpdateWorkEntryCommand
{

    public function __construct(
        private readonly string $userId,
        private readonly string $workEntryId,
        private readonly DateTimeImmutable $startDate,
        private readonly DateTimeImmutable $endDate,
    ) {}

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getWorkEntryId(): string
    {
        return $this->workEntryId;
    }


    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

}
