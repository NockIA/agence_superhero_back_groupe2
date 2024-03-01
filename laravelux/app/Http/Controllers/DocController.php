<?php

namespace App\Http\Controllers;

/**
* @OA\Tag(
*     name="Documentation",
*     description=""
* )
* @OA\Get(
*     path="/api/",
*     summary="get all routes info",
*     tags={"Documentation"},
*     @OA\Response(response=200, description="all routes")
* )
*/
class DocController extends Controller
{
    public function getDocu()
    {
        return response()->json(
            [
                "gestion user"=>[
                    "route 1 (post)"=>"/register",
                    "route 2 (post)"=>"/login",
                    "route 3 (post)"=>"/logout",
                    "route 4 (get)"=>"/users",  
                    "route 5 (put)"=>"/updateMyAccountEmail",     
                    "route 6 (put)"=>"/updateMyAccountFirstName",                
                    "route 7 (put)"=>"/updateMyAccountLastName",     
                    "route 8 (put)"=>"/updateMyAccountPassword",
                    "route 9 (put)"=>"/updateMyAccountImage",
                ],
                "gestion héros"=>[
                    "route 1 (get)"=>"/allHeros",
                    "route 2 (post)"=>"/addHero",
                    "route 3 (put)"=>"/updateHero",
                    "route 4 (get)"=>"/getOneHero/{uuid}",
                    "route 5 (delete)"=>"/hero/{uuid}",
                    "route 6 (get)"=>"/getAllHeroOfOneTeams/{uuid}",
                ], 
                "gestion plannets"=>[
                    "route 1 (get)"=>"/plannets",
                    "route 2 (post)"=>"/addPlannets",
                    "route 3 (put)"=>"/updatePlannets",
                    "route 4 (delete)"=>"/plannet/{id}",
                    "route 5 (get)"=> "/getOnePlannet/{id}",
                ], 
                "gestion gadgets"=>[
                    "route 1 (get)"=>"/gadgets",
                    "route 2 (post)"=>"/addGadget",
                    "route 3 (put)"=>"/updateGadget",
                    "route 4 (delete)"=>"/gadget/{id}",
                    "route 5 (post)"=>"/linkGadgetToHero",
                    "route 6 (get)"=> "/getOneGadget/{id}",
                ], 
                "gestion powers"=>[
                    "route 1 (get)"=>"/powers",
                    "route 2 (post)"=>"/addPower",
                    "route 3 (put)"=>"/updatePower",
                    "route 4 (delete)"=>"/power/{id}",
                    "route 5 (post)"=>"/linkPowerToHero",
                    "route 6 (get)"=> "/getOnePower/{id}",
                ], 
                "gestion vehicles"=>[
                    "route 1 (get)"=>"/vehicles",
                    "route 2 (post)"=>"/addVehicle",
                    "route 3 (put)"=>"/updateVehicle",
                    "route 4 (delete)"=>"/vehicle/{id}",
                    "route 5 (get)"=> "/getOneVehicle/{id}",
                ], 
                "gestion city"=>[
                    "route 1 (get)"=>"/city",
                    "route 2 (post)"=>"/linkCityToHero",
                    "route 3 (post)"=>"/addCity",
                    "route 4 (put)"=>"/updateCity",
                    "route 5 (delete)"=>"/city/{id}",
                    "route 6 (get)"=> "/getOneCity/{id}",
                ], 
                "gestion teams"=>[
                    "route 1 (get)"=>"/teams",
                    "route 2 (post)"=>"/addTeam",
                    "route 3 (put)"=>"/updateTeam",
                    "route 4 (delete)"=>"/team/{id}",
                    "route 5 (get)"=> "/getOneTeam/{id}",
                ], 
            ], 200);
    }
}
