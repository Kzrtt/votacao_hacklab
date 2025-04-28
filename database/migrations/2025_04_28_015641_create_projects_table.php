<?php
// database/migrations/xxxx_xx_xx_create_projects_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('prj_id');
            $table->unsignedBigInteger('event_evt_id');
            $table->string('prj_name');
            $table->text('prj_participants');
            $table->text('prj_stack');
            $table->timestamp('prj_created_at')->nullable();
            $table->timestamp('prj_updated_at')->nullable();

            $table->foreign('event_evt_id')
                  ->references('evt_id')
                  ->on('events')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
