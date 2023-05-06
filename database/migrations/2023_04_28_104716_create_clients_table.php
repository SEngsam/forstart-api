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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('firstname',21);
            $table->string('lastname',21);
            $table->string('phone',21)->unique();
            $table->string('photo')->nullable();
            $table->date('barithdate')->nullable();
            $table->tinyInteger('is_verified')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
