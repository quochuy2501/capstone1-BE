<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FootballPitch;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getDataFootball(Request $request)
    {
        $football_pitchs = FootballPitch::join('categories', 'football_pitches.id_category', 'categories.id')
            ->join("users", "users.id", "football_pitches.id_owner")
            ->select('football_pitches.*', 'categories.name_category');

        if($request->id_category != 0){
            $football_pitchs = $football_pitchs->where('football_pitches.id_category', $request->id_category);
        }
        if($request->name != ""){
            $football_pitchs = $football_pitchs->where('football_pitches.name', 'like' , '%' . $request->name . '%');
        }
        if($request->id_district != 0){
            $football_pitchs = $football_pitchs->where('users.id_district', $request->id_district);
        }
        if($request->id_ward != 0){
            $football_pitchs = $football_pitchs->where('users.id_ward', $request->id_ward);
        }
        if($request->price != 0){
            switch ($request->price) {
                case 1 :
                    $football_pitchs = $football_pitchs->where('football_pitches.price', '<', '200000');
                    break;
                case 2 :
                    $football_pitchs = $football_pitchs->where('football_pitches.price', '<', '400000');
                    $football_pitchs = $football_pitchs->where('football_pitches.price', '>=', '200000');
                    break;
                case 3 :
                    $football_pitchs = $football_pitchs->where('football_pitches.price', '>=', '400000');
                    break;
            };
        }
        if($request->date != ""){
            $football_pitchs = $football_pitchs->where('football_pitches.detailed_schedule','like', '%' . '"id":"Sat","label":"Thứ bảy","value":"open"' . '%');
        }
        
        $football_pitchs = $football_pitchs->paginate(2);
        if (count($football_pitchs) > 0) {
            return response()->json([
                'football_pitchs'  => $football_pitchs,
            ], 200);
        }
        return response()->json(['error' => 'There are no football pitches in the system'], 400);
    }
}
