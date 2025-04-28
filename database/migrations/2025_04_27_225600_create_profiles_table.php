<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('prf_id');
            $table->string('prf_name');
            $table->string('prf_status');
            $table->string('prf_entity');
            $table->timestamp('prf_created_at')->nullable();
            $table->timestamp('prf_updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profiles');
    }
};
