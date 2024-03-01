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
        Schema::create('superHeroGadgetRelation', function (Blueprint $table) {
            $table->id();
            $table->uuid('heroUuid');
            $table->foreign('heroUuid')
                  ->references('UUID')->on('superheros')
                  ->onDelete('CASCADE')
                  ->onUpdate('CASCADE');
            $table->unsignedBigInteger('gadgetId');
            $table->foreign('gadgetId')
                ->references('id')->on('gadgets')
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
        Schema::dropIfExists('superHeroGadgetRelation');
    }
};
