<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Citys;
use App\Models\SuperHero;
use App\Models\heroCityRelation;

/**
 * @OA\Tag(
 *     name="City",
 *     description="all routes related to cities management"
 * )
 * @OA\Get(
 *     path="/api/city",
 *     summary="get all city",
 *     tags={"City"},
 *     @OA\Response(response=200, description="all data for city")
 * )
 * @OA\Get(
 *     path="/api/getOneCity/{id}",
 *     summary="get one city info",
 *     tags={"City"},
 *     @OA\Response(response=200, description="all data from one city")
 * )
 * @OA\Post(
 *     path="/api/linkCityToHero",
 *     summary="add a link between hero and city",
 *     tags={"City"},
 *     @OA\Response(response=200, description="")
 * )
 * @OA\Post(
 *     path="/api/addCity",
 *     summary="add city to database",
 *     tags={"City"},
 *     @OA\Response(response=200, description="")
 * )
 *  * @OA\Delete(
 *     path="/api/City/{id}",
 *     summary="suprr city by id",
 *     tags={"City"},
 *     @OA\Response(response=201, description="")
 * )
 * @OA\Put(
 *     path="/api/updateCity",
 *     summary="update a city in database",
 *     tags={"City"},
 *     @OA\Response(response=201, description="")
 * ) 
 */
class City extends Controller
{
    public function getAllCity()
    {
        $data = Citys::all();
        return response()->json($data, 200);
    }
    public function addCity(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'planetLocationId' => 'required|integer|max:255|exists:plannets,id',
            ]);
            $user = $request->user();
            $rtn = Citys::create([
                'name' => $request->input('name'),
                'planetLocationId' => $request->input('planetLocationId'),
                'creatorId' => $user["id"],
            ]);
            return response()->json([$rtn], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function linkCityToHero(Request $request)
    {
        try {
            $request->validate([
                'heroUuid' => 'required|string|exists:superheros,uuid',
                'cityId' => 'required|integer|exists:city,id',
            ]);
            $superhero = Superhero::where('uuid', $request->input('heroUuid'))->first();
            $city = Citys::find($request->input('cityId'));
            $superhero->cities()->attach($city->id, ['heroUuid' => $superhero->UUID]);
            return response()->json(["status"=>"succes"], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function getOneCity(Request $request, $id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|string|exists:city,id',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $city = Citys::find($id);
            $user = $request->user();
            if ($city["creatorId"] == $user["id"]){
                $city["canModificate"] = true;
            } else {
                $city["canModificate"] = false;
            }
            return response()->json([$city], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function deleteCity(Request $request, $id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:city,id',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $user = $request->user();
            $city = Citys::where('id', $id)->get();
            if ($city[0]["creatorId"] == $user["id"] || $user["isAdmin"]){
                $res = Citys::where('id', $id)->delete();
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
    public function updateCity(Request $request)
    {
        try {
            $request->validate([
                'id'=> 'required|integer|exists:city,id',
                'name' => 'required|string|max:255',
                'planetLocationId' => 'required|integer|max:255|exists:plannets,id',
            ]);
            $user = $request->user();
            $city = Citys::where('id', $request->input('id'))->get();
            if ($city[0]["creatorId"] == $user["id"] || $user["isAdmin"]){
                $city = Citys::where('id', $request->input('id'))
                ->update([
                    'name' => $request->input('name'),
                    'planetLocationId' => $request->input('planetLocationId'),
                ]);
                return response()->json([$city], 201);
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
