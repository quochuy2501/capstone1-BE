<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getData()
    {
        $owner = User::where("id_role", 1)->get();
        if (count($owner) > 0) {
            return response()->json([
                'owners'  => $owner,
            ], 200);
        }
        return response()->json([
            'error'  => "There are no accounts in the system!",
        ], 400);
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
