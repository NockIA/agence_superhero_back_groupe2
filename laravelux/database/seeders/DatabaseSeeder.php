<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Plannets;
use App\Models\Citys;
use App\Models\Powers;
use App\Models\Gadgets;
use App\Models\Vehicles;
use App\Models\Superhero;
use App\Models\heroCityRelation;
use App\Models\heroPowerRelation;
use App\Models\heroGadgetRelation;
use App\Models\Teams;
use Ramsey\Uuid\Uuid;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'John.Doe@example.com',
            'password' => 'password',
            'linkProfileImage' => 'https://picsum.photos/id/237/200/300',
            'isAdmin' => true,
        ]);
        User::factory(30)->create();
        Plannets::factory(5)->create();
        $city = Citys::factory(5)->create();
        $power = Powers::factory(5)->create();
        $gadget = Gadgets::factory(5)->create();
        Vehicles::factory(5)->create();
        Teams::factory(5)->create();    
        $superheroes = Superhero::factory(10)->create();

        foreach ($superheroes as $superhero) {
            $superhero->powers()->attach($power->random()->id, ['heroUuid' => $superhero->uuid]);
        }
        
        foreach ($superheroes as $superhero) {
            $superhero->cities()->attach($city->random()->id, ['heroUuid' => $superhero->uuid]);
        }
        
        foreach ($superheroes as $superhero) {
            $superhero->gadgets()->attach($gadget->random()->id, ['heroUuid' => $superhero->uuid]);
        }
    }
}