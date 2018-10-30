<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaFoldersTable extends Migration
{
    public function up()
    {
        Schema::create('media_folders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->index()->nullable();
            $table->string('name');
            $table->timestamps();

            $table->foreign('parent_id')
                  ->references('id')
                  ->on('media_folders')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('media_folders');
    }
}
