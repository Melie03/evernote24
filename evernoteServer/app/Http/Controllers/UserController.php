<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

/**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to store a new User in the database.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email',
                'password' => 'required|string|min:8'
            ]);
            $request = $this->hashPassword($request);
            $user = User::create($request->all());

            DB::commit();
            return response()->json($user, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving user failed: " . $e->getMessage(), 500);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to get a User by its id.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to update a User in the database.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'required|string|min:8'
        ]);
        $validated = $this->hashPassword($validated);
        $user->update($validated);
        return response()->json($user);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to delete a User from the database.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }

    /**
     * @param Request $request
     * @return Request
     *
     * This method is used to hash the password of a user.
     
     */
    private function hashPassword (Request $request) : Request {
        $request ['password'] = Hash::make($request['password']);
        return $request;
    }

}
