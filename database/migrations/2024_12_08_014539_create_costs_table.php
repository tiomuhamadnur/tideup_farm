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
        Schema::create('costs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('price')->nullable();
            $table->bigInteger('qty')->nullable();
            $table->bigInteger('total_price')->nullable();
            $table->date('date')->nullable();
            $table->text('image')->nullable();
            $table->bigInteger('project_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('unit_id')->unsigned()->nullable();
            $table->uuid('uuid')->unique()->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('project_id')->on('projects')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('unit_id')->on('units')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('costs');
    }
};
