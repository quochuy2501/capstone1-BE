<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFootballPitchRequest;
use App\Models\FootballPitch;
use Illuminate\Http\Request;

class FootballPitchController extends Controller
{
    public function getData()
    {
        $owner = auth()->user();
        $football_pitchs = FootballPitch::join('categories', 'football_pitches.id_category', 'categories.id')
            ->where('id_owner', $owner->id)
            ->select('football_pitches.*', 'categories.name_category')
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
}
