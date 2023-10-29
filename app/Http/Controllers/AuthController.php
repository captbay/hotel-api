<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'username' => 'required|string|unique:users',
            'password' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'no_identitas' => 'required|string',
            'no_phone' => 'required|regex:/^(0)8[1-9][0-9]{6,10}$/',
            'address' => 'required|string',
        ]);

        //response error validation
        if ($validatedData->fails()) {
            return response()->json(['message' => $validatedData->errors()->all()], 422);
        }

        // create user customer
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'Customer',
        ]);

        // create customer
        $user->customer()->create([
            'name' => $request->name,
            'email' => $request->email,
            'no_identitas' => $request->no_identitas,
            'no_phone' => $request->no_phone,
            'address' => $request->address,
        ]);

        // return
        return response()->json([
            'success' => true,
            'message' => 'Register as customer successfully',
        ], 200);
    }


    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registerCustGroup(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'username' => 'required|string|unique:users',
            'password' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'no_identitas' => 'required|string',
            'no_phone' => 'required|regex:/^(0)8[1-9][0-9]{6,10}$/',
            'nama_insitusi' => 'required|string',
            'address' => 'required|string',
        ]);

        //response error validation
        if ($validatedData->fails()) {
            return response()->json(['message' => $validatedData->errors()->all()], 422);
        }

        // check siapa yang user role
        if (Auth::user()->role == 'SM') {
            $nama_insitusi = $request->nama_insitusi;

            // create user customer
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'Customer',
            ]);

            // create customer
            $user->customer()->create([
                'name' => $request->name,
                'email' => $request->email,
                'no_identitas' => $request->no_identitas,
                'no_phone' => $request->no_phone,
                'nama_insitusi' => $nama_insitusi,
                'address' => $request->address,
            ]);

            // return
            return response()->json([
                'success' => true,
                'message' => 'Register customer grup successfully',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to register',
            ], 409);
        }
    }


    /**
     * Login a user and create a new token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        //response error validation
        if ($validatedData->fails()) {
            return response()->json(['message' => $validatedData->errors()->all()], 422);
        }

        $user = User::where('username', $request->username)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'username user Not Found',
            ], 404);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        // if password is correct
        if (Hash::check($request->password, $user->password, [])) {
            // check role
            if ($user->role == 'Customer') {
                // get data customer
                $customer = $user->customer;

                return response()->json([
                    'data' => $customer,
                    'username' => $user->username,
                    'message' => 'Authenticated as a Customer active',
                    'role' => 'Customer',
                    'token_type' => 'Bearer',
                    'access_token' => $token
                ], 200);
            } else if ($user->role == 'Admin') {
                // get data admin
                $data = $user->pegawai;

                // return
                return response()->json([
                    'data' => $data,
                    'username' => $user->username,
                    'message' => 'Authenticated as a Admin active',
                    'role' => 'Admin',
                    'token_type' => 'Bearer',
                    'access_token' => $token
                ], 200);
            } else if ($user->role == 'SM') {
                $data = $user->pegawai;

                // return
                return response()->json([
                    'data' => $data,
                    'username' => $user->username,
                    'message' => 'Authenticated as a Sales Marketing active',
                    'role' => 'SM',
                    'token_type' => 'Bearer',
                    'access_token' => $token
                ], 200);
            } else if ($user->role == 'Owner') {
                $data = $user->pegawai;

                // return
                return response()->json([
                    'data' => $data,
                    'username' => $user->username,
                    'message' => 'Authenticated as a Owner active',
                    'role' => 'Owner',
                    'token_type' => 'Bearer',
                    'access_token' => $token
                ], 200);
            } else if ($user->role == 'FO') {
                $data = $user->pegawai;

                // return
                return response()->json([
                    'data' => $data,
                    'message' => 'Authenticated as a Front Office active',
                    'role' => 'FO',
                    'token_type' => 'Bearer',
                    'access_token' => $token
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Password false or user Not Found',
            ], 409);
        }
    }

    // change password
    public function changePassword(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'username' => 'required',
            'new_password' => 'required',
        ]);

        //response error validation
        if ($validatedData->fails()) {
            return response()->json(['message' => $validatedData->errors()->all()], 422);
        }

        $user = User::where('username', $request->username)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'username user Not Found',
            ], 404);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Password failed to change',
            ], 409);
        }
    }

    /**
     * Logout a user (revoke the token).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out',
        ]);
    }
}
