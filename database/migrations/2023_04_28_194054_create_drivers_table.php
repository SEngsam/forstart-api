<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('firstname',21);
            $table->string('lastname',21);
            $table->string('email')->unique();
            $table->date('barithdate')->nullable();
            $table->string('phone',21)->unique();
            $table->string('image')->nullable();
            $table->string('password');
            $table->string('identity_document')->nullable();
            $table->string('good_donduct_document')->nullable();
            $table->integer('referal_id')->nullable();
            $table->tinyInteger('is_verified')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
