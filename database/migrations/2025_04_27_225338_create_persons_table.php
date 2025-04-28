<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->bigIncrements('pes_id');
            $table->string('pes_name');
            $table->string('pes_email')->unique();
            $table->string('pes_function');
            $table->timestamp('pes_created_at')->nullable();
            $table->timestamp('pes_updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('persons');
    }
};
