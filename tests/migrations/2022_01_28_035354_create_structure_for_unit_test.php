<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStructureForUnitTest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

            Schema::create('model_ones', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });

            Schema::create('model_twos', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('model_one_id');
                $table->timestamps();
            });

    }


}
