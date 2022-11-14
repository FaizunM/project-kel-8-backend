<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("nis")->unique();
            $table->bigInteger("nisn")->unique();
            $table->string("fullname");
            $table->date("date_of_birth");
            $table->string("place_of_birth");
            $table->string("gender");
            $table->string("religion");
            $table->string("address");
            $table->foreignId("major_class_id");
            $table->foreignId("major_id");
            $table->string("email");
            $table->string("phone_number");
            $table->string("image")->nullable()->default(null);
            $table->string("role")->default("student");
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
        Schema::dropIfExists('students');
    }
};
