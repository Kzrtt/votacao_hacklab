<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('adm_id');
            $table->string('adm_fantasy');
            $table->timestamp('adm_created_at')->nullable();
            $table->timestamp('adm_updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admins');
    }
};
