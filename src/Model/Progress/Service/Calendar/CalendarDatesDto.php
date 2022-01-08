<?php declare(strict_types=1);

namespace App\Model\Progress\Service\Calendar;

class CalendarDatesDto
{
    private array $dates;
    private \DateTimeImmutable $firstDate;
    private \DateTimeImmutable $lastDate;

    public function __construct(
        array              $dates,
        \DateTimeImmutable $firstDate,
        \DateTimeImmutable $lastDate
    ) {
        $this->dates = $dates;
        $this->firstDate = $firstDate;
        $this->lastDate = $lastDate;
    }

    public function getDates(): array
    {
        return $this->dates;
    }

    public function getFirstDate(): \DateTimeImmutable
    {
        return $this->firstDate;
    }

    public function getLastDate(): \DateTimeImmutable
    {
        return $this->lastDate;
    }
}
