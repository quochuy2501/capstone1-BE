<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getData(Request $request)
    {
        $user = User::where("id_role", 0)->orderBy('id', 'DESC');
        if($request->is_block != ""){
            $user = $user->where('is_block', $request->is_block);
        }
        if($request->email != ""){
            $user = $user->where('email', 'like' , '%' . $request->email . '%');
        }
        $user = $user->paginate(5);
        if (count($user) > 0) {
            return response()->json([
                'users'  => $user,
            ], 200);
        }
        return response()->json([
            'error'  => "There are no accounts in the system!",
        ], 400);
    }

    public function updateStatus($id)
    {
        $user = User::where("id_role", 0)->where("id", $id)->first();
        if ($user) {
            $user->is_block = !$user->is_block;
            $user->save();

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
        $user = User::where("id_role", 0)->where("id", $id)->first();
        if ($user) {
            $user->delete();
            return response()->json([
                'message'  => "Delete user successfully",
            ], 200);
        }
        return response()->json([
            'error'  => 'An error has occurred',
        ], 400);
    }
}
