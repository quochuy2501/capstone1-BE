<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOwnerRequest;
use App\Models\User;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function getData(Request $request)
    {
        $owner = User::where("users.id_role", 1)
                        ->leftjoin("wards", "users.id_ward", "wards.id")
                        ->leftjoin("districts", "users.id_district", "districts.id")
                        ->select("users.*", "districts.name_district", "wards.name_ward")
                        ->orderBy('users.id', 'DESC');
        if($request->is_block != ""){
            $owner = $owner->where('users.is_block', $request->is_block);
        }
        if($request->email != ""){
            $owner = $owner->where('users.email', 'like' , '%' . $request->email . '%');
        }
        $owner = $owner->paginate(5);
        if (count($owner) > 0) {
            return response()->json([
                'owners'  => $owner,
            ], 200);
        }
        return response()->json([
            'error'  => "There are no accounts in the system!",
        ], 400);
    }

    public function createOwner(CreateOwnerRequest $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        User::create($data);
        return response()->json(['message' => 'Create owner successfully'], 200);
    }

    public function updateStatus($id)
    {
        $owner = User::where("id_role", 1)->where("id", $id)->first();
        if ($owner) {
            $owner->is_block = !$owner->is_block;
            $owner->save();

            return response()->json([
                'message'  => "Update status successfully",
            ], 200);
        }
        return response()->json([
            'error'  => 'An error has occurred',
        ], 400);
    }

    public function destroy($id)
    {
        $owner = User::where("id_role", 1)->where("id", $id)->first();
        if ($owner) {
            $owner->delete();
            return response()->json([
                'message'  => "Delete owner successfully",
            ], 200);
        }
        return response()->json([
            'error'  => 'An error has occurred',
        ], 400);
    }
}
