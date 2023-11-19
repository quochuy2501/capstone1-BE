<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FootballPitch;
use App\Models\Schedule;
use DateTime;
use Illuminate\Http\Request;
use stdClass;

class HomeController extends Controller
{
    public function getDataFootball(Request $request)
    {
        $football_pitchs = FootballPitch::join('categories', 'football_pitches.id_category', 'categories.id')
            ->join("users", "users.id", "football_pitches.id_owner")
            ->join("districts", "districts.id", "users.id_district")
            ->join("wards", "wards.id", "users.id_ward")
            ->select('football_pitches.*', 'categories.name_category', "users.address", "wards.name_ward", "districts.name_district");

        if ($request->id_category != 0) {
            $football_pitchs = $football_pitchs->where('football_pitches.id_category', $request->id_category);
        }
        if ($request->name != "") {
            $football_pitchs = $football_pitchs->where('football_pitches.name', 'like', '%' . $request->name . '%');
        }
        if ($request->id_district != 0) {
            $football_pitchs = $football_pitchs->where('users.id_district', $request->id_district);
        }
        if ($request->id_ward != 0) {
            $football_pitchs = $football_pitchs->where('users.id_ward', $request->id_ward);
        }
        if ($request->price != 0) {
            switch ($request->price) {
                case 1:
                    $football_pitchs = $football_pitchs->where('football_pitches.price', '<', '200000');
                    break;
                case 2:
                    $football_pitchs = $football_pitchs->where('football_pitches.price', '<', '400000');
                    $football_pitchs = $football_pitchs->where('football_pitches.price', '>=', '200000');
                    break;
                case 3:
                    $football_pitchs = $football_pitchs->where('football_pitches.price', '>=', '400000');
                    break;
            };
        }
        if ($request->date != "") {
            $tmp = '"id":"' . substr($request->date, 0, 3) . '","value":"open"';
            $football_pitchs = $football_pitchs->where('football_pitches.detailed_schedule', 'like', '%' . $tmp . '%');
        }

        $football_pitchs = $football_pitchs->paginate(2);
        if (count($football_pitchs) > 0) {
            return response()->json([
                'football_pitchs'  => $football_pitchs,
            ], 200);
        }
        return response()->json(['error' => 'There are no football pitches in the system'], 400);
    }

    public function getDataFootballById($id)
    {
        $football_pitchs = FootballPitch::join('categories', 'football_pitches.id_category', 'categories.id')
            ->join("users", "users.id", "football_pitches.id_owner")
            ->join("districts", "districts.id", "users.id_district")
            ->join("wards", "wards.id", "users.id_ward")
            ->select('football_pitches.*', 'categories.name_category', "users.address", "wards.name_ward", "districts.name_district")
            ->where("football_pitches.id", $id)
            ->first();
        if ($football_pitchs) {
            return response()->json([
                'football_pitch'  => $football_pitchs,
            ], 200);
        }
        return response()->json(['error' => 'There are no football pitches in the system'], 400);
    }

    public function getDataFootballAroundById($id)
    {
        $football_pitch_tmp = FootballPitch::join('categories', 'football_pitches.id_category', 'categories.id')
            ->join("users", "users.id", "football_pitches.id_owner")
            ->select('football_pitches.*', 'users.id_ward', "users.id_district")
            ->where("football_pitches.id", $id)
            ->first();

        $football_pitchs_war = FootballPitch::join('categories', 'football_pitches.id_category', 'categories.id')
            ->join("users", "users.id", "football_pitches.id_owner")
            ->join("districts", "districts.id", "users.id_district")
            ->join("wards", "wards.id", "users.id_ward")
            ->select('football_pitches.*', 'categories.name_category', "users.address", "wards.name_ward", "districts.name_district")
            ->where("football_pitches.id", "!=", $id)
            ->where("users.id_ward", $football_pitch_tmp->id_ward)
            ->get();
        $football_pitchs_dis = FootballPitch::join('categories', 'football_pitches.id_category', 'categories.id')
            ->join("users", "users.id", "football_pitches.id_owner")
            ->join("districts", "districts.id", "users.id_district")
            ->join("wards", "wards.id", "users.id_ward")
            ->select('football_pitches.*', 'categories.name_category', "users.address", "wards.name_ward", "districts.name_district")
            ->where("football_pitches.id", "!=", $id)
            ->where("users.id_district", $football_pitch_tmp->id_district)
            ->where("users.id_ward", "!=", $football_pitch_tmp->id_ward)
            ->get();
        $football_pitchs = $football_pitchs_war->concat($football_pitchs_dis);
        $football_pitchs->take(2);
        if (count($football_pitchs) > 0) {
            return response()->json([
                'football_pitchs'  => $football_pitchs,
            ], 200);
        }
        return response()->json(['error' => 'There are no football pitches in the system'], 400);
    }

    public function getSchedulePitch(Request $request)
    {
        $football_pitch = FootballPitch::where("id", $request->id)->first();
        if ($football_pitch) {
            $time_end = "";
            $time_start = "";
            $list_scheduled = [];
            $json = json_decode($football_pitch->detailed_schedule, true);
            foreach ($json as $value) {
                if (substr($request->date, 0, 3) == $value["id"] && $value["value"] == "open") {
                    $time_start = $value["startTime"];
                    $time_end = $value["endTime"];
                };
            }
            if ($time_start != null) {
                $dateTime = new DateTime($request->date);
                $schedules = Schedule::where("pitch_id", $football_pitch->id)->whereDate("date", $dateTime)
                    ->orderBy("time_start")->get();
                foreach ($schedules as $schedule_item) {
                    $tmp = new stdClass;
                    $tmp->time_start = $schedule_item->time_start;
                    $tmp->time_end = $schedule_item->time_end;
                    array_push($list_scheduled, $tmp);
                }
            }
            return response()->json([
                'time_start'  => $time_start,
                'time_end'  => $time_end,
                'list_scheduled' => $list_scheduled,
            ], 200);
        }
        return response()->json(['error' => 'There are no football pitches in the system'], 400);
    }

    public function setSchedulePitch(Request $request)
    {
        $football_pitch = FootballPitch::where("id", $request->id)->first();
        $check = true;
        $user = auth()->user();
        $dateTime = new DateTime($request->date);
        if ($football_pitch) {
            $time_end = "";
            $time_start = "";
            $json = json_decode($football_pitch->detailed_schedule, true);
            foreach ($json as $value) {
                if (substr($request->date, 0, 3) == $value["id"] && $value["value"] == "open") {
                    $time_start = $value["startTime"];
                    $time_end = $value["endTime"];
                    if ($time_start > $request->time_start || $time_end < $request->time_end) {
                        $check = false;
                    }
                };
            }
            if ($time_start != null) {
                $schedules = Schedule::where("pitch_id", $football_pitch->id)->whereDate("date", $dateTime)
                    ->orderBy("time_start")
                    ->get();
                foreach ($schedules as $schedule_item) {
                    if (($request->time_start < $schedule_item->time_end) && ($request->time_end > $schedule_item->time_start)) {
                        $check = false;
                    }
                }
            }
            if ($check) {
                $schedule = Schedule::where("user_id", $user->id)->where("payment_id", 0)->first();
                if($schedule){
                    $schedule->delete();
                }
                $hour = (strtotime($request->time_end) - strtotime($request->time_start))/3600;
                Schedule::create([
                    'pitch_id' => $football_pitch->id,
                    'user_id' => $user->id,
                    'date' => $dateTime->format('Y-m-d'),
                    'time_start' => $request->time_start,
                    'time_end' => $request->time_end,
                    'payment_id' => 0,
                    'total_hour' => $hour,
                    'total_price' => $hour * $football_pitch->price,
                ]);
                return response()->json([
                    'message'  => "Set the football field successfully",
                ], 200);
            }
            return response()->json(['error' => 'Invalid time'], 400);
        }
        return response()->json(['error' => 'There are no football pitches in the system'], 400);
    }
    public function getHistory() {
        $user = auth()->user();
        $schedules = Schedule::where("schedules.user_id", $user->id)
                                ->join("football_pitches", "football_pitches.id", "schedules.pitch_id")
                                ->leftjoin("invoices", "schedules.payment_id", "invoices.id")
                                ->select("schedules.*", "football_pitches.name as name_pitch")
                                ->orderBy("schedules.id","desc")
                                ->paginate(5);
        if(count($schedules) > 0){
            return response()->json(['schedule' => $schedules], 200);
        }
        return response()->json(['error' => 'There are no schedule football pitches in the system'], 400);
    }
}
