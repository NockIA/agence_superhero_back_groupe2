<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Superhero;
use App\Models\Citys;
use App\Models\Powers;
use App\Models\Gadgets;
use App\Models\Teams;
use App\Models\Vehicles;
use App\Models\Plannets;


use Ramsey\Uuid\Uuid;
/**
 * @OA\Tag(
 *     name="Hero",
 *     description="all routes related to hero management"
 * )
 * @OA\Get(
 *     path="/api/allHeros",
 *     summary="get all heros info",
 *     tags={"Hero"},
 *     @OA\Response(response=200, description="all data from heros")
 * )
 * @OA\Get(
 *     path="/api/getOneHero/{uuid}",
 *     summary="get one hero info",
 *     tags={"Hero"},
 *     @OA\Response(response=200, description="all data from one heros")
 * )
 * @OA\Get(
 *     path="/api/getAllHeroOfOneTeams/{uuid}",
 *     summary="get all heros info from team uuid",
 *     tags={"Hero"},
 *     @OA\Response(response=200, description="all data from heros from team uuid")
 * )
 * @OA\Post(
 *     path="/api/addHero",
 *     summary="add hero to database",
 *     tags={"Hero"},
 *     @OA\Response(response=201, description="")
 * ) 
 * @OA\Delete(
 *     path="/api/hero/{id}",
 *     summary="suprr power by id",
 *     tags={"Hero"},
 *     @OA\Response(response=201, description="")
 * )
 * @OA\Put(
 *     path="/api/updateHero",
 *     summary="update a hero in database",
 *     tags={"Hero"},
 *     @OA\Response(response=201, description="")
 * ) 
 *
 */
class superHeroController extends Controller
{
    public function getAllHero()
    {
        $data = SuperHero::leftJoin('teams', 'superheros.team', '=', 'teams.id')
            ->select('superheros.uuid', 'superheros.heroname as name', 'teams.name as team', 'superheros.linkImage')
            ->get();
        return response()->json($data, 200);
    }
    public function addHero(Request $request)
    {
        try {
            $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'heroname' => 'required|string|max:255',
                'sexe' => 'required|string|max:255',
                'hairColor' => 'required|string|max:255',
                'description' => 'required|string|max:500',
                'linkImage' => 'required|string|max:255',
                'team' => 'integer|exists:teams,id',
                'originPlannet' => 'required|integer|exists:plannets,id',
                'vehicle' => 'required|integer|exists:vehicles,id',
            ]);
            $user = $request->user();
            $hero = Superhero::create([
                'uuid' => Uuid::uuid4()->toString(),
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'heroname' => $request->input('heroname'),
                'sexe' => $request->input('sexe'),
                'hairColor' => $request->input('hairColor'),
                'description' => $request->input('description'),
                'linkImage' => $request->input('linkImage'),
                'team' => $request->input('team'),
                'originPlannet' => $request->input('originPlannet'),
                'vehicle' => $request->input('vehicle'),
                'creatorId' => $user["id"],
            ]);
            return response()->json([$hero], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function editHero(Request $request)
    {
        try {
            $request->validate([
                'uuid' => 'required|string|exists:superheros,uuid',
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'heroname' => 'required|string|max:255',
                'sexe' => 'required|string|max:255',
                'hairColor' => 'required|string|max:255',
                'description' => 'required|string|max:500',
                'linkImage' => 'required|string|max:255',
                'team' => 'required|integer|exists:teams,id',
                'originPlannet' => 'required|integer|exists:plannets,id',
            ]);
            $user = $request->user();
            $hero = Superhero::where('uuid', $request->input('uuid'))->get();
            if ($hero[0]["creatorId"] == $user["id"] || $user["isAdmin"]){
                $hero = Superhero::where('uuid', $request->input('uuid'))
                ->update([
                    'firstname' => $request->input('firstname'),
                    'lastname' => $request->input('lastname'),
                    'heroname' => $request->input('heroname'),
                    'sexe' => $request->input('sexe'),
                    'hairColor' => $request->input('hairColor'),
                    'description' => $request->input('description'),
                    'linkImage' => $request->input('linkImage'),
                    'team' => $request->input('team'),
                    'originPlannet' => $request->input('originPlannet'),
                ]);
                return response()->json([$hero], 201);
            } else {
                return response(['error' => 'Unauthorized'], 401);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function getOneHero(Request $request,$uuid)
    {
        try {
            $validator = Validator::make(['uuid' => $uuid], [
                'uuid' => 'required|string|exists:superheros,uuid',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $user = $request->user();
            $dataHero = SuperHero::where('uuid', $uuid)
                ->get();
            $cityData = Citys::join('superheroCityRelation', 'city.id','=', 'superheroCityRelation.cityId')
            ->where('superheroCityRelation.heroUuid',  $uuid)
            ->get();
            $powersData = Powers::join('superheroPowerRelation', 'powers.id','=', 'superheroPowerRelation.powerId')
            ->where('superheroPowerRelation.heroUuid',  $uuid)
            ->get();
            $gadgetsData = Gadgets::join('superHeroGadgetRelation', 'gadgets.id','=', 'superHeroGadgetRelation.gadgetId')
            ->where('superHeroGadgetRelation.heroUuid',  $uuid)
            ->get();
            if ($dataHero[0]['team'] !== null) {
                $teamsData = Teams::find($dataHero[0]['team']);
            } else{
                $teamsData = null;
            }
            if ($dataHero[0]['vehicle'] !== null) {
                $vehicleData = Vehicles::find($dataHero[0]['vehicle']);
            } else{
                $vehicleData = null;
            }
            if ($dataHero[0]['originPlannet'] !== null) {
                $originPlannetData = Plannets::find($dataHero[0]['originPlannet']);
            } else{
                $originPlannetData = null;
            }
            $dataHero[0]["city"] = $cityData;
            $dataHero[0]["powers"] = $powersData;
            $dataHero[0]["gadgets"] = $gadgetsData;
            $dataHero[0]["team"] = $teamsData;
            $dataHero[0]["vehicle"] = $vehicleData;
            $dataHero[0]["originPlannet"] = $originPlannetData;
            if ($dataHero[0]["creatorId"] == $user["id"]){
                $dataHero[0]["canModificate"] = true;
            } else {
                $dataHero[0]["canModificate"] = false;
            }
            return response()->json($dataHero, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function getAllHeroOfOneTeams($teamsId)
    {
        try {
            $validator = Validator::make(['teamsId' => $teamsId], [
                'teamsId' => 'required|integer|exists:teams,id',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $data = SuperHero::join('teams', 'superheros.team', '=', 'teams.id')
                ->where('teams.id', $teamsId)
                ->select('superheros.uuid', 'superheros.heroname as name', 'teams.name as team', 'superheros.linkImage')
                ->get();
            return response()->json($data, 200);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function deleteHero(Request $request, $uuid)
    {
        try {
            $validator = Validator::make(['uuid' => $uuid], [
                'uuid' => 'required|string|exists:superheros,uuid',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $user = $request->user();
            $hero = Superhero::where('uuid', $uuid)->get();
            if ($hero[0]["creatorId"] == $user["id"] || $user["isAdmin"]){
                $res = SuperHero::where('uuid', $uuid)->delete();
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
}
