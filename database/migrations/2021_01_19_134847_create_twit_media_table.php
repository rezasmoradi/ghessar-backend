<?php

use App\Models\TwitMedia;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwitMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('twit_id');
            $table->string('path');
            $table->enum('type', TwitMedia::MEDIA_TYPES);
            $table->string('mime_type');
            $table->bigInteger('size');
            $table->string('thumbnail')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('twit_id')
                ->references('id')
                ->on('twits')
                ->onDelete('no action')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_media');
    }
}
