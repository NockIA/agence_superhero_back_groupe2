<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Plannets;

/**
 * @OA\Tag(
 *     name="Plannet",
 *     description="all routes related to plannets management"
 * )
 * @OA\Get(
 *     path="/api/plannets",
 *     summary="get all gadgets info",
 *     tags={"Plannet"},
 *     @OA\Response(response=200, description="all data from plannets")
 * )
 * @OA\Get(
 *     path="/getOnePlannet/{id}",
 *     summary="get one plannet info",
 *     tags={"Plannet"},
 *     @OA\Response(response=200, description="all data from one plannet")
 * )
 * @OA\Post(
 *     path="/api/addPlannet",
 *     summary="add plannet to database",
 *     tags={"Plannet"},
 *     @OA\Response(response=201, description="")
 * ) 
 * @OA\Delete(
 *     path="/api/plannet/{id}",
 *     summary="suprr plannet by id",
 *     tags={"Plannet"},
 *     @OA\Response(response=201, description="")
 * )
 * @OA\Put(
 *     path="/api/updatePlannet",
 *     summary="update a plannet in database",
 *     tags={"Plannet"},
 *     @OA\Response(response=201, description="")
 * ) 
 */
class Plannet extends Controller
{
    public function getAllPlannets()
    {
        $data = Plannets::all();
        return response()->json($data, 200);
    }
    public function addPlannet(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:500',
                'linkImage' => 'required|string|max:255',
            ]);
            $user = $request->user();
            $rtn = Plannets::create([
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
    public function deletePlannet(Request $request, $id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:plannets,id',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $user = $request->user();
            $plannet = Plannets::where('id', $id)->get();
            if ($plannet[0]["creatorId"] == $user["id"] || $user["isAdmin"]){
                $res = Plannets::where('id', $id)->delete();
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
    public function updatePlannet(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:plannets,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:500',
                'linkImage' => 'required|string|max:255',
            ]);
            $user = $request->user();
            $plannet = Plannets::where('id', $request->input('id'))->get();
            if ($plannet[0]["creatorId"] == $user["id"] || $user["isAdmin"]){
                $plannet = Plannets::where('id', $request->input('id'))
                ->update([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'linkImage' => $request->input('linkImage'),
                ]);
                return response()->json([$plannet], 201);
            } else {
                return response(['error' => 'Unauthorized'], 401);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function getOnePlannet(Request $request, $id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|string|exists:plannets,id',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $plannet = Plannets::find($id);
            $user = $request->user();
            if ($plannet["creatorId"] == $user["id"]){
                $plannet["canModificate"] = true;
            } else {
                $plannet["canModificate"] = false;
            }
            return response()->json($plannet, 200);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
