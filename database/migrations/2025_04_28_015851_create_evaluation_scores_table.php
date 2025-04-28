<?php
// database/migrations/xxxx_xx_xx_create_evaluation_scores_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluation_scores', function (Blueprint $table) {
            $table->bigIncrements('es_id');
            $table->unsignedBigInteger('evaluation_eva_id');
            $table->unsignedBigInteger('criteria_crt_id');
            $table->decimal('es_final_score', 5, 2);

            $table->foreign('evaluation_eva_id')
                  ->references('eva_id')
                  ->on('evaluations')
                  ->onDelete('cascade');
            $table->foreign('criteria_crt_id')
                  ->references('crt_id')
                  ->on('criteria')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_scores');
    }
};
