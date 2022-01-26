<?php declare(strict_types=1);

namespace App\Model\Progress\Service\Calendar;

class CalendarService
{
    public function getCalendarDates(\DateTimeImmutable $date, \DateTimeZone $timezone): CalendarDatesDto
    {
        $rowNumber = 0;
        $rowIndex = 1;

        $nextDay = $date
            ->modify('first day of this month')
//            ->setTimezone($timezone)
            ->setTime(0, 0, 0)
            ->modify('this week Monday');

        $firstDate = $nextDay;

        $dates[$rowNumber][] = $nextDay;

        for ($i = 1; $i <= 41; $i++) {
            $nextDay = $nextDay->modify('+1 day');

            $dates[$rowNumber][] = $nextDay;

            $rowIndex++;

            if ($rowIndex >= 7) {
                $rowNumber++;
                $rowIndex = 0;
            }
        }

        $lastDate = $nextDay;

        return new CalendarDatesDto($dates, $firstDate, $lastDate);
    }
}
