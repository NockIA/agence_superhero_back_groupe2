<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Powers;
use App\Models\SuperHero;

/**
 * @OA\Tag(
 *     name="Power",
 *     description="all routes related to powers management"
 * )
 * @OA\Get(
 *     path="/api/powers",
 *     summary="get all powers info",
 *     tags={"Power"},
 *     @OA\Response(response=200, description="all data from powers")
 * )
 * @OA\Get(
 *     path="/api/getOnePower/{id}",
 *     summary="get one power info",
 *     tags={"Power"},
 *     @OA\Response(response=200, description="all data from one power")
 * )
 * @OA\Post(
 *     path="/api/linkPowerToHero",
 *     summary="add a link between hero and power",
 *     tags={"Power"},
 *     @OA\Response(response=200, description="")
 * )
 * @OA\Post(
 *     path="/api/addPower",
 *     summary="add power to database",
 *     tags={"Power"},
 *     @OA\Response(response=201, description="")
 * ) 
 * @OA\Delete(
 *     path="/api/power/{id}",
 *     summary="suprr power by id",
 *     tags={"Power"},
 *     @OA\Response(response=201, description="")
 * )
 * @OA\Put(
 *     path="/api/updatePower",
 *     summary="update a power in database",
 *     tags={"Power"},
 *     @OA\Response(response=201, description="")
 * ) 
 */
class Power extends Controller
{
    public function getAllPowers()
    {
        $data = Powers::all();
        return response()->json($data, 200);
    }
    public function addPower(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:500',
                'linkImage' => 'required|string|max:255',
            ]);
            $user = $request->user();
            $rtn = Powers::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'linkImage' => $request->input('linkImage'),
                'creatorId' => $user["id"],
            ]);
            return response()->json([$rtn], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function deletePower(Request $request, $id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:powers,id',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $user = $request->user();
            $gadget = Powers::where('id', $id)->get();
            if ($gadget[0]["creatorId"] == $user["id"] || $user["isAdmin"]){
                $res = Powers::where('id', $id)->delete();
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
    public function updatePower(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:powers,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:500',
                'linkImage' => 'required|string|max:255',
            ]);
            $user = $request->user();
            $power = Powers::where('id', $request->input('id'))->get();
            if ($power[0]["creatorId"] == $user["id"] || $user["isAdmin"]){
                $power = Powers::where('id', $request->input('id'))
                ->update([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'linkImage' => $request->input('linkImage'),
                ]);
                return response()->json([$power], 201);
            } else {
                return response(['error' => 'Unauthorized'], 401);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function linkPowerToHero(Request $request)
    {
        try {
            $request->validate([
                'heroUuid' => 'required|string|exists:superheros,uuid',
                'powerId' => 'required|integer|exists:powers,id',
            ]);
            $superhero = Superhero::where('uuid', $request->input('heroUuid'))->first();
            $power = Powers::find($request->input('powerId'));
            $superhero->powers()->attach($power->id, ['heroUuid' => $superhero->UUID]);
            return response()->json(["status"=>"succes"], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function getOnePower(Request $request, $id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|string|exists:powers,id',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $power = Powers::find($id);
            $user = $request->user();
            if ($power["creatorId"] == $user["id"]){
                $power["canModificate"] = true;
            } else {
                $power["canModificate"] = false;
            }
            return response()->json([$power], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
