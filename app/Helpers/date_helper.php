<?php

use Carbon\Carbon;

if (!function_exists('getDaysOfCurrentWeek')) {
    /**
     * Get all days of the current week.
     *
     * @return array
     */
    function getDaysOfCurrentWeek()
    {
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY); // Start of the week (Monday)
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);     // End of the week (Sunday)

        $days = [];
        for ($date = $startOfWeek; $date->lte($endOfWeek); $date->addDay()) {
            $days[] = $date->copy()->format('Y-m-d'); // Add each day as a Carbon instance
        }

        return $days;
    }

}
