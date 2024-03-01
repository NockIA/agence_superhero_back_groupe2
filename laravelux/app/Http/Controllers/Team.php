<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Models\Teams;
/**
 * @OA\Tag(
 *     name="Teams",
 *     description="all routes related to teams management"
 * )
 * @OA\Get(
 *     path="/api/teams",
 *     summary="get all teams info",
 *     tags={"Teams"},
 *     @OA\Response(response=200, description="all data from teams")
 * )
 * @OA\Get(
 *     path="/api/getOneTeam/{id}",
 *     summary="get one team info",
 *     tags={"Teams"},
 *     @OA\Response(response=200, description="all data from one team")
 * )
 * @OA\Post(
 *     path="/api/addTeam",
 *     summary="add team to database",
 *     tags={"Teams"},
 *     @OA\Response(response=201, description="")
 * ) 
 * @OA\Delete(
 *     path="/api/team/{id}",
 *     summary="suprr team by id",
 *     tags={"Teams"},
 *     @OA\Response(response=201, description="")
 * )
 * @OA\Put(
 *     path="/api/updateTeam",
 *     summary="update a team in database",
 *     tags={"Teams"},
 *     @OA\Response(response=201, description="")
 * ) 
 */
class Team extends Controller
{
    public function getAllTeams()
    {
        $data = Teams::all();
        return response()->json($data, 200);
    }
    public function addTeam(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $user = $request->user();
            $rtn = Teams::create([
                'name' => $request->input('name'),
                'creatorId' => $user["id"],
            ]);
            return response()->json([$rtn], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function getOneTeam(Request $request, $id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|string|exists:teams,id',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $team = Teams::find($id);
            $user = $request->user();
            if ($team["creatorId"] == $user["id"]){
                $team["canModificate"] = true;
            } else {
                $team["canModificate"] = false;
            }
            return response()->json([$team], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function deleteTeam(Request $request, $id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:teams,id',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $user = $request->user();
            $team = Teams::where('id', $id)->get();
            if ($team[0]["creatorId"] == $user["id"] || $user["isAdmin"]){
                $res = Teams::where('id', $id)->delete();
                return response()->json($res, 201);
            } else {
                return response(['error' => 'Unauthorized'], 401);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function updateTeam(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:teams,id',
                'name' => 'required|string|max:255',
            ]);
            $user = $request->user();
            $team = Teams::where('id', $request->input('id'))->get();
            if ($team[0]["creatorId"] == $user["id"] || $user["isAdmin"]){
                $team = Teams::where('id', $request->input('id'))
                ->update([
                    'name' => $request->input('name'),
                ]);
                return response()->json([$team], 201);
            } else {
                return response(['error' => 'Unauthorized'], 401);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
