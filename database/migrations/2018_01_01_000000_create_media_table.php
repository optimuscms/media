<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMediaTable extends Migration
{
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->unsignedInteger('folder_id')->index()->nullable();

            $table->foreign('folder_id')
                  ->references('id')
                  ->on('media_folders')
                  ->onDelete('cascade');
        });

        Schema::create('mediables', function (Blueprint $table) {
            $table->unsignedInteger('media_id')->index();
            $table->unsignedInteger('mediable_id')->index();
            $table->string('mediable_type');
            $table->string('collection');

            $table->foreign('media_id')
                  ->references('id')
                  ->on('media')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mediables');
        Schema::dropIfExists('media');
    }
}
