<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Gadgets;
use App\Models\SuperHero;

/**
 * @OA\Tag(
 *     name="Gadget",
 *     description="all routes related to gadgets management"
 * )
 * @OA\Get(
 *     path="/api/gadgets",
 *     summary="get all gadgets info",
 *     tags={"Gadget"},
 *     @OA\Response(response=200, description="all data from gadgets")
 * )
 * @OA\Get(
 *     path="/getOneGadget/{id}",
 *     summary="get one gadget info",
 *     tags={"Gadget"},
 *     @OA\Response(response=200, description="all data from one gadget")
 * )
 * @OA\Post(
 *     path="/api/linkGadgetToHero",
 *     summary="add a link between hero and gadget",
 *     tags={"Gadget"},
 *     @OA\Response(response=200, description="")
 * )
 * @OA\Post(
 *     path="/api/addGadget",
 *     summary="add gadget to database",
 *     tags={"Gadget"},
 *     @OA\Response(response=201, description="")
 * ) 
 * @OA\Delete(
 *     path="/api/gadget/{id}",
 *     summary="suprr gadget by id",
 *     tags={"Gadget"},
 *     @OA\Response(response=201, description="")
 * )
 * @OA\Put(
 *     path="/api/updateGadget",
 *     summary="update a gadget in database",
 *     tags={"Gadget"},
 *     @OA\Response(response=201, description="")
 * ) 
 */
class Gadget extends Controller
{
    public function getAllGadgets()
    {
        $data = Gadgets::all();
        return response()->json($data, 200);
    }
    public function addGadget(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:500',
                'linkImage' => 'required|string|max:255',
            ]);
            $user = $request->user();
            $rtn = Gadgets::create([
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
    public function deleteGadget(Request $request, $id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:gadgets,id',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $user = $request->user();
            $gadget = Gadgets::where('id', $id)->get();
            if ($gadget[0]["creatorId"] == $user["id"] || $user["isAdmin"]){
                $res = Gadgets::where('id', $id)->delete();
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
    public function updateGadget(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:gadgets,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:500',
                'linkImage' => 'required|string|max:255',
            ]);
            $user = $request->user();
            $gadget = Gadgets::where('id', $request->input('id'))->get();
            if ($gadget[0]["creatorId"] == $user["id"] || $user["isAdmin"]){
                $gadget = Gadgets::where('id', $request->input('id'))
                ->update([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'linkImage' => $request->input('linkImage'),
                ]);
                return response()->json([$gadget], 201);
            } else {
                return response(['error' => 'Unauthorized'], 401);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function linkGadgetToHero(Request $request)
    {
        try {
            $request->validate([
                'heroUuid' => 'required|string|exists:superheros,uuid',
                'gadgetId' => 'required|integer|exists:gadgets,id',
            ]);
            $superhero = Superhero::where('uuid', $request->input('heroUuid'))->first();
            $gadget = Gadgets::find($request->input('gadgetId'));
            $superhero->gadgets()->attach($gadget->id, ['heroUuid' => $superhero->UUID]);
            return response()->json(["status"=>"succes"], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function getOneGadget(Request $request, $id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|string|exists:gadgets,id',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $gadget = Gadgets::find($id);
            $user = $request->user();
            if ($gadget["creatorId"] == $user["id"]){
                $gadget["canModificate"] = true;
            } else {
                $gadget["canModificate"] = false;
            }
            return response()->json([$gadget], 201);
            
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
