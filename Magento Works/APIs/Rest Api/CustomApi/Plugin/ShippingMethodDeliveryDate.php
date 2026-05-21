<?php

namespace Codilar\CustomApi\Plugin;

use DatePeriod;
use Exception;
use Magento\Quote\Api\ShippingMethodManagementInterface;
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Psr\Log\LoggerInterface as PsrLogger;
use Codilar\CustomApi\Model\Helper\Data;

class ShippingMethodDeliveryDate
{
    /**
     * @var DateTime
     */
    private DateTime $dateTime;

    /**
     * @var PsrLogger
     */
    private PsrLogger $logger;

    /**
     * @var Data
     */
    private Data $data;

    /**
     * @param DateTime $dateTime
     * @param PsrLogger $logger
     * @param Data $data
     */
    public function __construct(
        DateTime $dateTime,
        PsrLogger $logger,
        Data $data
    ) {
        $this->dateTime = $dateTime;
        $this->logger = $logger;
        $this->data = $data;
    }

    /**
     * Modify the result of the getList method.
     *
     * @param ShippingMethodManagementInterface $subject
     * @param ShippingMethodInterface[] $result
     * @return ShippingMethodInterface[]
     * @throws Exception
     */
    public function afterGetList(ShippingMethodManagementInterface $subject, $result)
    {
        $minDay = null;
        $availableDates = [];
        $unAvailableDates = [];

        try {
            foreach ($result as $shippingMethod) {
               // if ($shippingMethod->getMethodCode() == "amstrates17" || $shippingMethod->getMethodCode() == "amstrates1") {
                    $minDay = $shippingMethod->getExtensionAttributes()->getAmdeliverydateChannelConfig()->getMin();
                    $maxDay = $shippingMethod->getExtensionAttributes()->getAmdeliverydateChannelConfig()->getMax();
                    $schedulers = $shippingMethod->getExtensionAttributes()->getAmdeliverydateDateScheduleItems();
                    foreach ($schedulers as $schedule) {
                        $form = null;
                        $to = null;
                        $allDates = null;
                        // type -0 Specific Date Range
                        if ($schedule->getData('type') == "0") {
                            $from = $schedule->getFrom();
                            $to = $schedule->getTo();
                            $allDates = $this->getDatesBetween($from, $to);
                            if ($schedule->getIsAvailable() == 1) {
                                $availableDates[] = $allDates;
                            } else {
                                $unAvailableDates[] = $allDates;
                            }
                        } // type -1 = Days Of Year
                        elseif ($schedule->getData('type') == "1") {
                            $from = $schedule->getFrom();
                            $to = $schedule->getTo();
                            $allDates = $this->getDatesForCurrentAndNextYear($from, $to);
                            if ($schedule->getIsAvailable() == 1) {
                                $availableDates[] = $allDates;
                            } else {
                                $unAvailableDates [] = $allDates;
                            }
                        } // type -2 = Dates of Month
                        elseif ($schedule->getData('type') == "2") {
                            $from = $schedule->getFrom();
                            $to = $schedule->getTo();
                            $allDates = $this->getSameDayDates($from, $to, $minDay);
                            if ($schedule->getIsAvailable() == 1) {
                                $availableDates[] = $allDates;
                            } else {
                                $unAvailableDates[] = $allDates;
                            }
                        } // type -3 = Days of week
                        elseif ($schedule->getData('type') == "3") {
                            $from = $schedule->getFrom();
                            $to = $schedule->getTo();
                            $allDates = $this->collectHolidayDatesWithSameDayOfWeek($from, $to, $schedule->getIsAvailable());
                            $unAvailableDates[] = $allDates;
                        }
                    }
                    $mergedDates = [];
                    foreach ($unAvailableDates as $innerArray) {
                        $mergedDates = array_merge($mergedDates, $innerArray);
                    }
                    $uniqueExceptionDates = array_unique($mergedDates);
                    $minDateUpdated = $this->calculateUpdatedMinDate($minDay, $uniqueExceptionDates);
                    $toDays = $maxDay - 1; // minus one as we need to considered todays date as well
                    $date = $this->data->getCurrentStoreDate()->format('Y-m-d');// current date

                    $calenderCalculation = $this->modifyMax($date, 0, $toDays, $maxDay, $uniqueExceptionDates);
                    $exceptionDays = $calenderCalculation["exceptions"];
                    $endDate = $calenderCalculation["end"];

                    $currentDate = new \DateTime($date);
                    $startDate = new \DateTime($date);
                    $minDate = $startDate->modify("+$minDay days");

                    // to excluding the exception days greater than max & calculate the start dat --- start
                    $invalidDates = [];
                    $additionalMinDays = [];
                    foreach ($exceptionDays as $dateToCheckString) {
                        $dateToCheck = new \DateTime($dateToCheckString);
                        $end = new \DateTime($dateToCheckString);
                        // Check if the date falls between the start and end dates
                        if ($dateToCheck >= $currentDate && $dateToCheck <= $minDate) {
                            $additionalMinDays[] = $dateToCheck->format('Y-m-d');
                        }
                        if ($dateToCheck > $endDate) {
                            $invalidDates[] = $dateToCheck->format('Y-m-d');
                        }
                    }

                    // to excluding the exception days greater than max & calculate the start date  --- end
                    // Final variable to send to frontend - start
                    $minDay += count($additionalMinDays) - 1;
                    $max = $calenderCalculation["max"];
                    //Getting all the available dates
                    $availableShipmentDates =
                        $this->getAvailabeDatesForShipment($uniqueExceptionDates, $minDateUpdated, $max);
                    $shippingMethod->getExtensionAttributes()->getAmdeliverydateChannelConfig()->setMin($minDateUpdated);
                    $shippingMethod->getExtensionAttributes()->getAmdeliverydateChannelConfig()->setMax($max);
                    $shippingMethod->getExtensionAttributes()->setServerTime($this->data->getServerTime());
                    $shippingMethod->getExtensionAttributes()->setAvailableDates($availableShipmentDates);
                }

              //  }

            return $result;
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
            return $result;
        }
    }


    /**
     * Get all the available dates
     *
     * @param array $holyDays
     * @param string $minDateUpdated
     * @param string $max
     * @return array
     */
    public function getAvailabeDatesForShipment($holyDays, $minDateUpdated, $max)
    {
        $availableDates = [];
        $current_date = date('Y-m-d', strtotime("+$minDateUpdated days"));
        $end_date = date('Y-m-d', strtotime("+$max days"));
        while ($current_date < $end_date) {
            if (!in_array($current_date, $holyDays)) {
                $availableDates[] = $current_date;
            }
            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
        }
        return $availableDates;
    }

    /**
     * @param $min_working_count
     * @param $min_date
     * @param $max_date
     * @param $non_working_dates
     * @return |null
     */
    public function calculateUpdatedMinDate($min_working_count, $non_working_dates)
    {
        $non_working_dates = $this->removeTodayFromArray($non_working_dates);
        $current_date = $this->data->getCurrentStoreDate();
        $working_count = -1;
        $updatedMinday = (int) $min_working_count;
        while ($working_count < $min_working_count) {
            // Check if the current date is not a non-working day
            if (!in_array($current_date->format('Y-m-d'), $non_working_dates)) {
                $working_count++;
            } else {
                $updatedMinday++;
            }
            // Move to the next day
            $current_date->modify('+1 day');
            // Move to the next day
        }
        return $updatedMinday;
    }

    /**
     * @param $dates
     * @return array
     */
    public function removeTodayFromArray($dates)
    {
        $today = date('Y-m-d');
        $filtered_dates = array_filter($dates, function ($date) use ($today) {
            return date('Y-m-d', strtotime($date)) != $today;
        });
        return $filtered_dates;
    }

    /**
     * Type -0
     *
     * @param $start_date
     * @param $end_date
     * @return array
     * @throws Exception
     */
    public function getDatesBetween($start_date, $end_date): array
    {
        $dates = [];
        $start_date = new \DateTime($start_date);
        $end_date = new \DateTime($end_date);

        while ($start_date <= $end_date) {
            $dates[] = $start_date->format('Y-m-d');
            $start_date->modify('+1 day');
        }

        return $dates;
    }

    /**
     * Type -1
     *
     * @param $start_date
     * @param $end_date
     * @return array
     */
    public function getDatesForCurrentAndNextYear($start_date, $end_date): array
    {
        $current_year = date('Y');
        $next_year = date('Y', strtotime('+1 year'));

        $start_date = date("$current_year-m-d", strtotime($start_date));
        $end_date = date("$current_year-m-d", strtotime($end_date));

        $next_year_start_date = date("$next_year-m-d", strtotime($start_date));
        $next_year_end_date = date("$next_year-m-d", strtotime($end_date));

        $dates_array = [];

        $current_date = $start_date;
        while ($current_date <= $end_date) {
            $dates_array[] = $current_date;
            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
        }

        $current_date = $next_year_start_date;
        while ($current_date <= $next_year_end_date) {
            $dates_array[] = $current_date;
            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
        }
        return $dates_array;
    }

    /**
     * Type - 2
     *
     * @param string $startDate
     * @param string $endDate
     * @param int $minDay
     * @return array
     * @throws Exception
     */
    public function getSameDayDates($startDate, $endDate, $minDay): array
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $end = $end->modify('+1 day'); // Add one day to include the end date
        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($start, $interval, $end);

        $days = [];
        foreach ($dateRange as $date) {
            $days[] = $date->format('d');
        }

        $uniqueDays = array_unique($days);
        $result = [];

        foreach ($uniqueDays as $day) {
            $currentDate = new \DateTime();
            //Current month date include
            if (!empty($minDay)) {
                $currentDay = date('Y-m-d', strtotime("+$minDay days"));
                $formatDay = \DateTime::createFromFormat('Y-m-d', $currentDate->format('Y-m') . '-' . $day);
                $formatDay = $formatDay->format('Y-m-d');
                if ($currentDay <= $formatDay) {
                    $result[] = $formatDay;
                }
                //current moth date include in the case of +min date is greater than format date
                if ($currentDate->format('Y-m-d') <= $formatDay) {
                    $result[] = $formatDay;
                }
            }

            for ($i = 0; $i < 3; $i++) {
                $currentDate->modify('first day of next month');
                $targetDate = \DateTime::createFromFormat('Y-m-d', $currentDate->format('Y-m') . '-' . $day);
                if ($targetDate !== false && $targetDate->format('d') == $day) {
                    $result[] = $targetDate->format('Y-m-d'); // Adding dates directly to result array
                }
            }
        }

        return $result;
    }

    /**
     * Type -3
     *
     * @param $date1
     * @param $date2
     * @param $isAvailable
     * @return array
     */
    public function collectHolidayDatesWithSameDayOfWeek($date1, $date2, $isAvailable): array
    {
        // Get the day of the week for date1 and date2
        $day_of_week_date1 = date('N', strtotime($date1)); // N returns 1 for Monday, 7 for Sunday
        $day_of_week_date2 = date('N', strtotime($date2));

        // Collect all dates in array for the next three months
        $dates_in_next_three_months = [];
        $left_dates_in_next_three_months = [];

        $current_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime('+3 months'));

        while ($current_date <= $end_date) {
            $current_day_of_week = date('N', strtotime($current_date));

            if ($day_of_week_date1 <= $day_of_week_date2) {
                if ($current_day_of_week >= $day_of_week_date1 && $current_day_of_week <= $day_of_week_date2) {
                    $dates_in_next_three_months[] = $current_date;
                } else {
                    $left_dates_in_next_three_months[] = $current_date;
                }
            } else {
                // Handling the case where day_of_week_date1 > day_of_week_date2 (e.g., Monday to Wednesday)
                if ($current_day_of_week >= $day_of_week_date1 || $current_day_of_week <= $day_of_week_date2) {
                    $dates_in_next_three_months[] = $current_date;
                } else {
                    $left_dates_in_next_three_months[] = $current_date;
                }
            }


            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
        }
        if ($isAvailable == 1) {
            return $left_dates_in_next_three_months;
        } else {
            return $dates_in_next_three_months;
        }
        return $dates_in_next_three_months;
    }


    /**
     * //
     * @param string $date
     * @param int $min
     * @param int $max
     * @param int $maxCount
     * @param string[] $exceptions
     * @return array
     * @throws Exception
     */
    public function modifyMax($date, $min, $max, $maxCount, $exceptions = []): array
    {
        $startDate = new \DateTime($date);
        if ($min) {
            $startDate = $startDate->modify("+$min days");
        }
        $newDate = new \DateTime($startDate->format("Y-m-d"));
        $endDate = $newDate->modify("+$max days");
        asort($exceptions);
        $exceptions = array_filter(array_unique($exceptions));
        $count = 0;
        foreach ($exceptions as $dateToCheckString) {
            $dateToCheck = new \DateTime($dateToCheckString);
            // Check if the date falls between the start and end dates
            if ($dateToCheck >= $startDate && $dateToCheck <= $endDate) {
                $count++;
            }
        }
        $maxCount += $count;
        // $maxCount > 365 given to limit the max one year date or prevent infinite loop
        if ($count == 0 || $maxCount > 365) {
            return ["max" => $maxCount, "end" => $endDate, "exceptions" => $exceptions];
        }
        $date = $endDate->format("Y-m-d");
        //return ["max" => $maxCount, "end" => $endDate, "exceptions" => $exceptions];
        // recurring function calling itself until it get the last date
        return $this->modifyMax($date, 1, $count - 1, $maxCount, $exceptions);
    }
}
