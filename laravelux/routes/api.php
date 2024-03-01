<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\superHeroController;
use App\Http\Controllers\Plannet;
use App\Http\Controllers\Gadget;
use App\Http\Controllers\Power;
use App\Http\Controllers\Vehicle;
use App\Http\Controllers\Team;
use App\Http\Controllers\City;
use App\Http\Controllers\DocController;
use App\Http\Middleware\ServerRestrictionMiddleware;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware([ServerRestrictionMiddleware::class])->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get("/",[DocController::class, 'getDocu']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/users', [UserController::class, 'getAllUser']);
        Route::get('/getOneUser/{id}', [UserController::class, 'getOneUser']);
        Route::delete('/user/{id}', [UserController::class, 'deleteUser']);
        Route::get('/getMyInfo', [UserController::class, 'getMyInfo']);
        Route::delete('/deleteMyAccount', [UserController::class, 'deleteMyAccount']);
        Route::put('/updateMyAccountEmail', [UserController::class, 'updateUserEmail']);
        Route::put('/updateMyAccountFirstName', [UserController::class, 'updateUserFirstName']);
        Route::put('/updateMyAccountLastName', [UserController::class, 'updateUserLastName']);
        Route::put('/updateMyAccountPassword', [UserController::class, 'updateUserPassword']);
        Route::put('/updateMyAccountImage', [UserController::class, 'updateUserImage']);


        Route::get('/allHeros', [superHeroController::class, 'getAllHero']);
        Route::post('/addHero', [superHeroController::class, 'addHero']);
        Route::put('/updateHero', [superHeroController::class, 'editHero']);
        Route::get('/getOneHero/{uuid}', [superHeroController::class, 'getOneHero']);
        Route::delete('/hero/{uuid}', [superHeroController::class, 'deleteHero']);
        Route::get('/getAllHeroOfOneTeams/{uuid}', [superHeroController::class, 'getAllHeroOfOneTeams']);

        Route::get('/plannets', [Plannet::class, 'getAllPlannets']);
        Route::get('/getOnePlannet/{id}', [Plannet::class, 'getOnePlannet']);
        Route::post('/addPlannets', [Plannet::class, 'addPlannet']);
        Route::put('/updatePlannets', [Plannet::class, 'updatePlannet']);
        Route::delete('/plannet/{id}', [Plannet::class, 'deletePlannet']);

        Route::get('/gadgets', [Gadget::class, 'getAllGadgets']);
        Route::get('/getOneGadget/{id}', [Gadget::class, 'getOneGadget']);
        Route::post('/addGadget', [Gadget::class, 'addGadget']);
        Route::post('/linkGadgetToHero', [Gadget::class, 'linkGadgetToHero']);
        Route::put('/updateGadget', [Gadget::class, 'updateGadget']);
        Route::delete('/gadget/{id}', [Gadget::class, 'deleteGadget']);

        Route::get('/powers', [Power::class, 'getAllPowers']);
        Route::get('/getOnePower/{id}', [Power::class, 'getOnePower']);
        Route::post('/addPower', [Power::class, 'addPower']);
        Route::post('/linkPowerToHero', [Power::class, 'linkPowerToHero']);
        Route::put('/updatePower', [Power::class, 'updatePower']);
        Route::delete('/power/{id}', [Power::class, 'deletePower']);

        Route::get('/vehicles', [Vehicle::class, 'getAllVehicles']);
        Route::get('/getOneVehicle/{id}', [Vehicle::class, 'getOneVehicle']);
        Route::post('/addVehicle', [Vehicle::class, 'addVehicle']);
        Route::put('/updateVehicle', [Vehicle::class, 'updateVehicle']);
        Route::delete('/vehicle/{id}', [Vehicle::class, 'deleteVehicle']);

        Route::get('/cities', [City::class, 'getAllCity']);
        Route::get('/getOneCity/{id}', [City::class, 'getOneCity']);
        Route::post('/linkCityToHero', [City::class, 'linkCityToHero']);
        Route::post('/addCity', [City::class, 'addCity']);
        Route::put('/updateCity', [City::class, 'updateCity']);
        Route::delete('/city/{id}', [City::class, 'deleteCity']);

        Route::get('/teams', [Team::class, 'getAllTeams']);
        Route::get('/getOneTeam/{id}', [Team::class, 'getOneTeam']);
        Route::post('/addTeam', [Team::class, 'addTeam']);
        Route::delete('/team/{id}', [Team::class, 'deleteTeam']);
        Route::put('/updateTeam', [Team::class, 'updateTeam']);
    });
});