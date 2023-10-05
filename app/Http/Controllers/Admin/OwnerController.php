<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOwnerRequest;
use App\Models\User;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function getData()
    {
        $user = User::where("id_role", 0)->get();
        if (count($user) > 0) {
            return response()->json([
                'users'  => $user,
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
