<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeywordRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keyword_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedbigInteger('keyword_id');
            $table->foreign('keyword_id')->references('id')->on('keywords')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('contact')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keyword_records');
    }
}
