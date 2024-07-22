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
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id',true);
            $table->integer('type_id')->index()->unsigned();
            $table->foreign('type_id')->references('id')->on('user_type')->onDelete('cascade');
            $table->string('name',50)->nullable();
            $table->string('email',150)->unique()->nullable();
            $table->string('phone',150)->unique()->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
