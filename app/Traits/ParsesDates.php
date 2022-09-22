<?php
namespace App\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

trait ParsesDates
{
    /**
     * Formats a UTC date and time string for display without changing the timezone.
     */
    public function toFormattedGlobalDateTime($globalDateTimeStr)
    {
        return Date::parse($globalDateTimeStr, 'UTC')->format('m/d/Y h:i:s A');
    }

    /**
     * Formats a UTC date and time string for display after converting to the local timezone.
     */
    public function toFormattedLocalDateTime($globalDateTimeStr)
    {
        return $this->toLocalDateTime($globalDateTimeStr)->format('m/d/Y g:i A');
    }

    /**
     * Formats a UTC date string for display after converting to the local timezone.
     */
    public function toFormattedLocalDate($globalDateTimeStr)
    {
        return $this->toLocalDateTime($globalDateTimeStr)->format('m/d/Y');
    }

    /**
     * Converts a UTC date and time string to Carbon object set to local timezone.
     */
    public function toLocalDateTime($globalDateTimeStr)
    {
        return Date::parse($globalDateTimeStr, 'UTC')->setTimezone($this->getDateParseTimezone());
    }

    /**
     * Converts a local date and time string to Carbon object set to UTC.
     */
    public function fromLocalDateTime($localDateTimeStr)
    {
        return Date::parse($localDateTimeStr, $this->getDateParseTimezone())->setTimezone('UTC');
    }

    public function startOfDayGlobalDateTime($dateStr)
    {
        return Date::parse($dateStr, $this->getDateParseTimezone())
            ->startOfDay()
            ->setTimezone('UTC');
    }

    public function endOfDayGlobalDateTime($dateStr)
    {
        return Date::parse($dateStr, $this->getDateParseTimezone())
            ->endOfDay()
            ->setTimezone('UTC');
    }

    public function todayGlobalDate()
    {
        return Date::today($this->getDateParseTimezone())
            ->setTimezone('UTC')
            ->toDateString();
    }

    public function startOfMonthGlobalDate()
    {
        return Date::parse('first day of this month', $this->getDateParseTimezone())
            ->setTimezone('UTC')
            ->toDateString();
    }

    public function endOfMonthGlobalDate()
    {
        return Date::parse('last day of this month', $this->getDateParseTimezone())
            ->setTimezone('UTC')
            ->toDateString();
    }

    public function aYearAgoGlobalDate()
    {
        return Date::today($this->getDateParseTimezone())
            ->subYear()
            ->setTimezone('UTC')
            ->toDateString();
    }

    private function getDateParseTimezone()
    {
        return Auth::check() ? Auth::user()->getTimezone() : 'America/New_York';
    }
}
