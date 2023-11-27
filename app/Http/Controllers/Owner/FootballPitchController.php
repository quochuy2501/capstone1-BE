<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFootballPitchRequest;
use App\Models\FootballPitch;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FootballPitchController extends Controller
{
    public function getData()
    {
        $owner = auth()->user();
        $football_pitchs = FootballPitch::join('categories', 'football_pitches.id_category', 'categories.id')
            ->where('id_owner', $owner->id)
            ->select('football_pitches.*', 'categories.name_category')
            ->orderBy('id', 'DESC')
            ->paginate(5);
        if (count($football_pitchs) > 0) {
            return response()->json([
                'football_pitchs'  => $football_pitchs,
            ], 200);
        }
        return response()->json(['error' => 'There are no football pitches in the system'], 400);
    }


    public function store(CreateFootballPitchRequest $request)
    {
        $owner = auth()->user();
        $data = $request->all();
        $data['id_owner'] = $owner->id;
        $image_tmp = "";
        for( $i = 0; $i < 4; $i++){
            $image_obj = "image_" . $i;
            if(isset($request[$image_obj])){
                $response = cloudinary()->upload($request[$image_obj]->getRealPath())->getSecurePath();
                if($i == 0) {
                    $image_tmp = $image_tmp . $response;
                }else{
                    $image_tmp = $image_tmp."," . $response;
                }
            }
        }
        $data["image"] = $image_tmp;
        FootballPitch::create($data);
        return response()->json([
            'message' => 'Successfully added a new football field',
        ], 200);
    }

    public function destroy($id)
    {
        $owner = auth()->user();
        $football_pitchs = FootballPitch::where("id", $id)->where("id_owner", $owner->id)->first();
        if ($football_pitchs) {
            $football_pitchs->delete();

            return response()->json([
                'message' => 'Successfully delete a football field',
            ], 200);
        }
        return response()->json([
            'error' => "The football field is not correct",
        ], 400);
    }

    public function getDataById($id)
    {
        $owner = auth()->user();
        $football_pitchs = FootballPitch::where("id", $id)->where("id_owner", $owner->id)->first();
        if ($football_pitchs) {
            return response()->json([
                'football_pitchs'      => $football_pitchs,
            ]);
        }
        return response()->json([
            'error' => "The football field is not correct",
        ], 400);
    }


    public function update(Request $request)
    {
        $owner = auth()->user();
        $football_pitchs = FootballPitch::where("id", $request->id)->where("id_owner", $owner->id)->first();
        $data = $request->all();
        $image_tmp = "";
        for( $i = 0; $i < 4; $i++){
            $image_obj = "image_" . $i;
            if(isset($request[$image_obj])){
                if(!is_string($request[$image_obj])){
                    $response = cloudinary()->upload($request[$image_obj]->getRealPath())->getSecurePath();
                }else{
                    $response = $request[$image_obj];
                }
                if($i == 0) {
                    $image_tmp = $image_tmp . $response;
                }else{
                    $image_tmp = $image_tmp."," . $response;
                }
            }
        }
        $data["image"] = $image_tmp;
        if ($football_pitchs) {
            $football_pitchs->update($data);
            return response()->json([
                'message' => 'Successfully update a football field',
            ], 200);
        }
        return response()->json([
            'error' => "The football field is not correct",
        ], 400);
    }
    public function getScheduleInMonth(Request $request) {
        $user = auth()->user();
        $date = Carbon::createFromFormat('m/d/Y', $request->month)->format('Y-m-d');

        $end     = Carbon::create(date("Y-m-t", strtotime($date)));
        $begin   = Carbon::create(date("Y-m-01", strtotime($date)));
        $schedules = Schedule::where("football_pitches.id_owner", $user->id)
                                ->whereDate('schedules.date', '>=', $begin)
                                ->whereDate('schedules.date', '<=', $end)
                                ->join("football_pitches", "football_pitches.id", "schedules.pitch_id")
                                ->leftjoin("invoices", "schedules.payment_id", "invoices.id")
                                ->select("schedules.*", "football_pitches.name as name_pitch")
                                ->orderBy("schedules.date")
                                ->get();
        if(count($schedules) > 0){
            return response()->json(['schedule' => $schedules], 200);
        }
        return response()->json(['error' => 'There are no schedule football pitches in the system'], 400);
    }

    public function getScheduleInDate(Request $request) {
        $user = auth()->user();
        $date = Carbon::createFromFormat('m/d/Y', $request->date)->format('Y-m-d');

        $schedules = Schedule::where("football_pitches.id_owner", $user->id)
                                ->whereDate('schedules.date', '=', $date)
                                ->join("football_pitches", "football_pitches.id", "schedules.pitch_id")
                                ->join("users", "users.id", "football_pitches.id_owner")
                                ->join("wards", "users.id_ward", "wards.id")
                                ->join("districts", "users.id_district", "districts.id")
                                ->leftjoin("invoices", "schedules.payment_id", "invoices.id")
                                ->select("schedules.*", "football_pitches.name as name_pitch", "users.phone", "users.address", "districts.name_district", "wards.name_ward")
                                ->orderBy("schedules.date")
                                ->get();
        if(count($schedules) > 0){
            return response()->json(['schedule' => $schedules], 200);
        }
        return response()->json(['error' => 'There are no schedule football pitches in the system'], 400);
    }

    public function getTotalMoney() {
        $user = auth()->user();
        $end     = Carbon::now();
        $begin   = Carbon::create(date("Y-1-1", strtotime($end)));
        $data = Schedule::where("football_pitches.id_owner", $user->id)
                                ->whereDate('invoices.updated_at', '>=', $begin)
                                ->whereDate('invoices.updated_at', '<=', $end)
                                ->join("football_pitches", "football_pitches.id", "schedules.pitch_id")
                                ->join("invoices", "schedules.payment_id", "invoices.id")
                                ->select(DB::raw("DATE_FORMAT(invoices.updated_at, '%m-%Y') as month"),  DB::raw('sum(invoices.total_money) as total_money'))
                                ->groupBy('month')
                                ->get();

        $arr_month = [];
        $data_new = [];
        foreach ($data as $value) {
            array_push($arr_month, substr($value->month, 0, 2));
            $data_new[$value->month] = $value->total_money;
        }
        for ($i = $begin->month; $i < ($end->month + 1); $i++) {
            if (!in_array($i, $arr_month)) {
                if ($i < 10) {
                    $month = '0' . ($i) . '-' . $end->year;
                    $data_new[$month] = 0;
                } else {
                    $month = ($i) . '-' . $end->year;
                    $data_new[$month] = 0;
                }
            }
        }
        ksort($data_new);
        return response()->json(['data' => $data_new], 200);
    }
}
