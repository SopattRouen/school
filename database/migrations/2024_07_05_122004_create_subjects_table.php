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
        Schema::create('subject', function (Blueprint $table) {
            $table->increments('id',true);
            $table->integer('subject_id')->index()->unsigned();
            $table->foreign('subject_id')->references('id')->on('types')->onDelete('cascade');
            $table->string('name',150)->nullable();
            $table->string('icon',150)->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject');
    }
};
