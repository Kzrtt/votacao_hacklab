<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('criteria', function (Blueprint $table) {
            // adiciona a coluna de explicação após o peso
            $table->text('crt_explanation')
                  ->nullable()
                  ->after('crt_weight');
        });
    }

    public function down()
    {
        Schema::table('criteria', function (Blueprint $table) {
            $table->dropColumn('crt_explanation');
        });
    }
};
