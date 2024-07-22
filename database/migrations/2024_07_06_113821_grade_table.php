<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->increments('id',true);
            $table->integer('type_id')->index()->unsigned();
            $table->unsignedInteger('score_id');
            $table->float('average')->default(0);
            $table->string('grade');
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('user')->onDelete('cascade');
            $table->foreign('score_id')->references('id')->on('score')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('grades');
    }
};
