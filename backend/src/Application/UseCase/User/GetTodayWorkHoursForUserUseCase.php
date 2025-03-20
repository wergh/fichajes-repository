<?php

declare(strict_types=1);

namespace App\Application\UseCase\User;

use App\Domain\User\Entity\User;
use App\Domain\WorkEntry\Repository\WorkEntryReadRepositoryInterface;
use DateTimeImmutable;

/**
 * Use case for calculating today's work hours for a user.
 */
final readonly class GetTodayWorkHoursForUserUseCase
{
    /**
     * Constructor for GetTodayWorkHoursForUserUseCase.
     * 
     * @param WorkEntryReadRepositoryInterface $workEntryReadRepository Repository for reading work entries.
     */
    public function __construct(
        private WorkEntryReadRepositoryInterface $workEntryReadRepository,
    )
    {
    }

    /**
     * Execute the use case to calculate today's work hours for a user.
     * 
     * @param User $user The user entity to calculate work hours for.
     * @return int The total work hours for today in seconds.
     */
    public function execute(User $user): int
    {
        $workEntries = $this->workEntryReadRepository->getWorkEntriesForToday((string) $user->getId());

        return $this->calculateWorkHoursForToday($workEntries);
    }

    /**
     * Calculate the total work hours for today from an array of work entries.
     * 
     * @param array $workEntries The array of work entries.
     * @return int The total work hours for today in seconds.
     */
    private function calculateWorkHoursForToday(array $workEntries): int
    {
        $now = new DateTimeImmutable('now');
        $startOfDay = (new DateTimeImmutable('today'))->setTime(0, 0);
        $endOfDay = (new DateTimeImmutable('tomorrow'))->setTime(0, 0)->modify('-1 second');

        $totalSeconds = 0;

        foreach ($workEntries as $entry) {
            $startDate = $entry->getStartDate();
            $endDate = $entry->getEndDate() ?? $now;

            if ($startDate < $startOfDay) {
                $startDate = $startOfDay;
            }

            if ($endDate > $endOfDay) {
                $endDate = $endOfDay;
            }

            $totalSeconds += $endDate->getTimestamp() - $startDate->getTimestamp();
        }
        return $totalSeconds;
    }
}