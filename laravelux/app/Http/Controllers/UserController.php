<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="User",
 *     description="all routes related to user management"
 * )
 * @OA\Get(
 *     path="/api/users",
 *     summary="get all users info",
 *     tags={"User"},
 *     @OA\Response(response=200, description="all data from users")
 * )
 * @OA\Get(
 *     path="/api/getOneUser/{id}",
 *     summary="get one user info",
 *     tags={"User"},
 *     @OA\Response(response=200, description="all data from one user")
 * )
 * @OA\Get(
 *     path="/api/getMyInfo",
 *     summary="get my user info",
 *     tags={"User"},
 *     @OA\Response(response=200, description="all data from my user account")
 * )
 * @OA\Post(
 *     path="/api/register",
 *     summary="add user to database",
 *     tags={"User"},
 *     @OA\Response(response=201, description="")
 * ) 
 * @OA\Delete(
 *     path="/api/user/{id}",
 *     summary="suprr user by id",
 *     tags={"User"},
 *     @OA\Response(response=201, description="")
 * )
 * @OA\Delete(
 *     path="/api/deleteMyAccount",
 *     summary="suprr my account",
 *     tags={"User"},
 *     @OA\Response(response=201, description="")
 * )
 * @OA\Put(
 *     path="/api/updateMyAccountEmail",
 *     summary="update user email in database",
 *     tags={"User"},
 *     @OA\Response(response=201, description="")
 * )
 * @OA\Put(
 *     path="/api/updateMyAccountFirstName",
 *     summary="update user firstname in database",
 *     tags={"User"},
 *     @OA\Response(response=201, description="")
 * )
 * @OA\Put(
 *     path="/api/updateMyAccountLastName",
 *     summary="update user last name in database",
 *     tags={"User"},
 *     @OA\Response(response=201, description="")
 * )
 * @OA\Put(
 *     path="/api/updateMyAccountPassword",
 *     summary="update user password in database",
 *     tags={"User"},
 *     @OA\Response(response=201, description="")
 * )
 * @OA\Put(
 *     path="/api/updateMyAccountImage",
 *     summary="update user image in database",
 *     tags={"User"},
 *     @OA\Response(response=201, description="")
 * )
 */
class UserController extends Controller
{
    public function getAllUser()
    {
        $data = User::all();
        return response()->json($data, 201);
    }
    public function register(Request $request)
    {

        try {
            $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
            ]);
            $user = User::create([
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'linkProfileImage'  => 'https://picsum.photos/id/237/200/300',
                'isAdmin' => false,
            ]);
            $token = $user->createToken('conn')->plainTextToken;
            return response()->json(['token' => $token], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'succes' => 'false',
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function getOneUser(Request $request, $id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|string|exists:users,id',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            return User::find($id);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function getMyInfo(Request $request)
    {
        $user = $request->user();
        return User::find($user["id"]);
    }
    public function deleteUser($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:users,id',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $res = User::where('id', $id)->delete();
            return response()->json($res, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function deleteMyAccount(Request $request)
    {
        $user = $request->user();
        $res = User::where('id', $user["id"])->delete();
        return response()->json($res, 201);
    }
    public function updateUserImage(Request $request)
    {
        try {
            $request->validate([
                'linkProfileImage' => 'required|string|max:255',
            ]);
            $user = $request->user();
            $rtn = User::where('id', $user["id"])
                ->update([
                    'linkProfileImage'  => $request->input('linkProfileImage'),
                ]);
            return response()->json([$rtn], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function updateUserEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|max:255',
            ]);
            $user = $request->user();
            $rtn = User::where('id', $user["id"])
                ->update([
                    'email'  => $request->input('email'),
                ]);
            return response()->json([$rtn], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function updateUserFirstName(Request $request)
    {
        try {
            $request->validate([
                'firstName' => 'required|string|max:255',
            ]);
            $user = $request->user();
            $rtn = User::where('id', $user["id"])
                ->update([
                    'firstName'  => $request->input('firstName'),
                ]);
            return response()->json([$rtn], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function updateUserLastName(Request $request)
    {
        try {
            $request->validate([
                'lastName' => 'required|string|max:255',
            ]);
            $user = $request->user();
            $rtn = User::where('id', $user["id"])
                ->update([
                    'lastName'  => $request->input('lastName'),
                ]);
            return response()->json([$rtn], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function updateUserPassword(Request $request)
    {
        try {
            $request->validate([
                'password' => 'required|string|min:8',
            ]);
            $user = $request->user();
            $rtn = User::where('id', $user["id"])
                ->update([
                    'password'  => bcrypt($request->input('password')),
                ]);
            return response()->json([$rtn], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
