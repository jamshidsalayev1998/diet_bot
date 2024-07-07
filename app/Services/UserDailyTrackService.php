<?php

namespace App\Services;

use App\Models\Track\DailyTrackReport;
use App\Models\V1\UserInfo;
use App\Traits\TelegramMessageLangsTrait;

class UserDailyTrackService
{
    use TelegramMessageLangsTrait;
    public static function my_results_text($userInfo)
    {
        $text = '';
        $text .= self::lang('your_earned_track_score') . ': ' . $userInfo->track_scores . PHP_EOL . PHP_EOL;
        $textStatus = '';
        switch ($userInfo->track_status) {
            case 0:
                $textStatus = 'track_status_not_calculated';
                break;
            case 1:
                $textStatus = 'track_status_unsatisfied';
                break;
            case 2:
                $textStatus = 'track_status_satisfactory';
                break;
            case 3:
                $textStatus = 'track_status_good';
                break;
            case 4:
                $textStatus = 'track_status_super';
                break;
            default:
                $textStatus = 'track_status_unsatisfied';
        }

        $text .= self::lang('your_track_result_of_this_week') . ': ' . PHP_EOL . PHP_EOL;
        $daysOfCurrentWeek = getDaysOfCurrentWeek();
        $reportsForWeek = DailyTrackReport::where('chat_id', $userInfo->chat_id)->whereIn('date_report', $daysOfCurrentWeek)->get()->groupBy('date_report')->toArray();
        foreach ($daysOfCurrentWeek as $dayOfCurrentWeek) {
            $resultThisDay = null;
            if (key_exists($dayOfCurrentWeek, $reportsForWeek)) {
                $resultThisDay = $reportsForWeek[$dayOfCurrentWeek];
            }
            $textResultDay = '⏱';
            if ($resultThisDay) {
                switch ($resultThisDay[0]['answer']) {
                    case 2:
                        $textResultDay = 'track_result_day_full_followed';
                        break;
                    case 1:
                        $textResultDay = 'track_result_day_partically_followed';
                        break;
                    case 0:
                        $textResultDay = 'track_result_day_not_followed';
                        break;
                    default:
                        $textResultDay = '⏱';
                }
            } else {
                if ($dayOfCurrentWeek < date('Y-m-d')) {
                    $textResultDay = '';
                }
            }
            $text .= WeekDayService::get_week_day_on_word($dayOfCurrentWeek) . ': ' . self::lang($textResultDay) . PHP_EOL . PHP_EOL;
        }
        return $text;
    }

    public static function liga_results_text($userInfo)
    {
        $topUsers = UserInfo::orderBy('track_scores', 'desc')->take(10)->get();

        // Retrieve the special user
        $specialUser = $userInfo;

        // // Calculate the rank of the special user
        $specialUserRank = UserInfo::where('track_scores', '>', $specialUser->track_scores)->count() + 1;

        // // Check if the special user is already in the top 10
        $isSpecialUserInTop10 = $topUsers->contains('chat_id', $userInfo->chat_id);

        if (!$isSpecialUserInTop10) {
            // Add special user to the list with their rank
            $topUsers->push($specialUser);
        }
        $text = '';
        $index = 1;
        foreach ($topUsers as $topUser) {
            $text .= $index . ' - ';
            switch ($index) {
                case 1:
                    $text .= '🥇';
                    break;
                case 2:
                    $text .= '🥈';
                    break;
                case 3:
                    $text .= '🥉';
                    break;
                default:
            }
            if ($topUser->chat_id == $userInfo->chat_id) {
                $text .= '<strong>' . $topUser->fio . '</strong>' . PHP_EOL . PHP_EOL;
            } else {
                $text .= $topUser->fio . PHP_EOL . PHP_EOL;
            }
            $index++;
        }
        return $text;
        // return json_encode($topUsers[0]->fio);

        // return response()->json([
        //     'status' => 'success',
        //     'top_users' => $topUsers,
        //     'special_user' => [
        //         'user' => $specialUser,
        //         'rank' => $specialUserRank,
        //     ]
        // ]);
    }
}
