<?php
// database/migrations/xxxx_xx_xx_create_evaluations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->bigIncrements('eva_id');
            $table->unsignedBigInteger('project_prj_id');
            $table->unsignedBigInteger('judge_jdg_id');
            $table->timestamp('eva_created_at')->nullable();
            $table->timestamp('eva_updated_at')->nullable();

            $table->foreign('project_prj_id')
                  ->references('prj_id')
                  ->on('projects')
                  ->onDelete('cascade');
            $table->foreign('judge_jdg_id')
                  ->references('jdg_id')
                  ->on('judges')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluations');
    }
};
