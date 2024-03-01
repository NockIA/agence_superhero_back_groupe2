<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('superheroCityRelation', function (Blueprint $table) {
            $table->id();
            $table->uuid('heroUuid');
            $table->foreign('heroUuid')
                  ->references('UUID')->on('superheros')
                  ->onDelete('CASCADE')
                  ->onUpdate('CASCADE');
            $table->unsignedBigInteger('cityId');
            $table->foreign('cityId')
                ->references('id')->on('city')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('superheroCityRelation');
    }
};
