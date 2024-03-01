<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Vehicles;
/**
 * @OA\Tag(
 *     name="Vehicle",
 *     description="all routes related to vehicles management"
 * )
 * @OA\Get(
 *     path="/api/vehicles",
 *     summary="get all vehicles info",
 *     tags={"Vehicle"},
 *     @OA\Response(response=200, description="all data from vehicles")
 * )
 * @OA\Get(
 *     path="/api/getOneVehicle/{id}",
 *     summary="get one vehicle info",
 *     tags={"Vehicle"},
 *     @OA\Response(response=200, description="all data from one vehicle")
 * )
 * @OA\Post(
 *     path="/api/addVehicle",
 *     summary="add vehicle to database",
 *     tags={"Vehicle"},
 *     @OA\Response(response=201, description="")
 * ) 
 * @OA\Delete(
 *     path="/api/vehicle/{id}",
 *     summary="suprr a vehicle by id",
 *     tags={"Vehicle"},
 *     @OA\Response(response=201, description="")
 * )
 * @OA\Put(
 *     path="/api/updateVehicle",
 *     summary="update a vehicle in database",
 *     tags={"Vehicle"},
 *     @OA\Response(response=201, description="")
 * ) 
 */
class Vehicle extends Controller
{
    public function getAllVehicles()
    {
        $data = Vehicles::all();
        return response()->json($data, 200);
    }
    public function addVehicle(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:500',
                'linkImage' => 'required|string|max:255',
            ]);
            $user = $request->user();
            $rtn = Vehicles::create([
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
    public function deleteVehicle(Request $request,$id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:vehicles,id',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $user = $request->user();
            $Vehicle = Vehicles::where('id', $id)->get();
            if ($Vehicle[0]["creatorId"] == $user["id"] || $user["isAdmin"]){
                $res = Vehicles::where('id', $id)->delete();
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
    public function updateVehicle(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:vehicles,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:500',
                'linkImage' => 'required|string|max:255',
            ]);
            $user = $request->user();
            $vehicle = Vehicles::where('id', $request->input('id'))->get();
            if ($vehicle[0]["creatorId"] == $user["id"] || $user["isAdmin"]){
                $vehicle = Vehicles::where('id', $request->input('id'))
                ->update([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'linkImage' => $request->input('linkImage'),
                ]);
                return response()->json([$vehicle], 201);
            } else {
                return response(['error' => 'Unauthorized'], 401);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function getOneVehicle(Request $request, $id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|string|exists:vehicles,id',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $vehicle = Vehicles::find($id);
            $user = $request->user();
            if ($vehicle["creatorId"] == $user["id"]){
                $vehicle["canModificate"] = true;
            } else {
                $vehicle["canModificate"] = false;
            }
            return response()->json([$vehicle], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
