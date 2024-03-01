<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('superheros', function (Blueprint $table) {
            $table->uuid('UUID')->primary();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('heroname');
            $table->string('sexe');
            $table->string('hairColor');
            $table->string('description');
            $table->string("linkImage");
            $table->unsignedBigInteger("team")->nullable();
            $table->foreign('team')
                ->references('id')->on('teams')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
            $table->unsignedBigInteger("originPlannet")->nullable();
            $table->foreign('originPlannet')
                ->references('id')->on('plannets')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
            $table->unsignedBigInteger("vehicle")->nullable();
            $table->foreign('vehicle')
                ->references('id')->on('vehicles')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
            $table->unsignedBigInteger("creatorId");
            $table->foreign('creatorId')
                ->references('id')->on('users')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('superheros');
    }
};
