<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        // VALIDATE DATA
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response([
                'user' => null,
                'message' => 'Neuspesna validacija.',
                'errors' => $validator->messages(),
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                "user" => null,
                "message" => "Nije tacna lozinka.",
            ], 401);
        }

        $token = $user->createToken('token');

        return response([
            "user" => $user,
            "message" => "Uspesno logovanje",
            'token' => $token->plainTextToken,
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        $user->tokens()->delete();

        return response([
            "user" => $user,
            "message" => "Uspesno ste se izlogovali.",
        ], 200);
    }

    public function register(Request $request)
    {
        try {
            // VALIDATE DATA
            $validator = Validator::make($request->all(), [
                'name' => 'required|alpha|min:2',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:4|confirmed',
            ]);

            if ($validator->fails()) {
                return response([
                    'user' => null,
                    'message' => 'Nesupesna validacija.',
                    'errors' => $validator->messages(),
                ], 400);
            }

            $user = new User();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);

            $user->save();
            $token = $user->createToken('token');

            return response([
                "user" => $user,
                "message" => "Korisnik je registrovan.",
                'token' => $token->plainTextToken,
            ], 201);
        } catch (Exception $e) {
            return response([
                "user" => null,
                "message" => $e->getMessage(),
            ], 500);
        }
    }
}
