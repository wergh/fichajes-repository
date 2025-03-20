<?php

declare(strict_types=1);

namespace App\Application\Mapper\WorkEntry;

use App\Application\Dto\WorkEntry\WorkEntryDto;
use App\Domain\WorkEntry\Entity\WorkEntry;

/**
 * Mapper class for converting WorkEntry entities to WorkEntryDto.
 */
class WorkEntryDtoMapper
{
    /**
     * Convert a WorkEntry entity to a WorkEntryDto.
     * 
     * @param WorkEntry $workEntry The work entry entity to convert.
     * @return WorkEntryDto The converted WorkEntryDto.
     */
    public function toDTO(WorkEntry $workEntry): WorkEntryDto
    {
        return new WorkEntryDto(
            (string) $workEntry->getId(),
            $workEntry->getStartDate()->format('Y-m-d H:i:s'),
            ($workEntry->getEndDate()) ? $workEntry->getEndDate()->format('Y-m-d H:i:s') : null,
        );
    }
}