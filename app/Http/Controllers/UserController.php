<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Menampilkan semua user
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    // Menampilkan user berdasarkan ID
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user, 200);
    }

    // Menambahkan user baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    // Mengupdate user
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'unique:users,username,' . $id,
            'password' => 'nullable|min:6',
            'email' => 'email|unique:users,email,' . $id,
            'phone' => 'unique:users,phone,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user->update([
            'username' => $request->username ?? $user->username,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'phone' => $request->phone ?? $user->phone,
        ]);

        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }

    // Menghapus user
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
