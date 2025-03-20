<?php

declare(strict_types=1);

namespace App\Application\Dto\WorkEntry;

use DateTimeImmutable;

/**
 * Data Transfer Object for Work Entry.
 */
class WorkEntryDto
{
    /**
     * Constructor for WorkEntryDto.
     * 
     * @param string $id The work entry ID.
     * @param string $startDate The start date of the work entry.
     * @param string|null $endDate The end date of the work entry, or null if not set.
     */
    public function __construct(
        public readonly string $id,
        public readonly string $startDate,
        public readonly ?string $endDate,
    ) {}
}