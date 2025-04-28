<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_represented_agents', function (Blueprint $table) {
            // PK customizado
            $table->bigIncrements('ura_id');

            // coluna para o tipo de morph (ex: App\Models\AgentType)
            $table->string('ura_type');

            // coluna para o ID do agente representado
            $table->unsignedBigInteger('represented_agent_id');

            // FK para users.usr_id
            $table->unsignedBigInteger('users_usr_id');
            $table->foreign('users_usr_id')
                  ->references('usr_id')
                  ->on('users')
                  ->onDelete('cascade');

            // sem timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_represented_agents');
    }
};
