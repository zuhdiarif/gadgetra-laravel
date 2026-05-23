<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id('ID');
            $table->string('Nama', 255)->nullable();
            $table->string('Email', 50)->unique();
            $table->string('password', 255);
            $table->integer('umur')->nullable();
            $table->string('tempat_lahir', 255)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('phone_keluarga', 50)->nullable();
            $table->text('alamat')->nullable();
            $table->string('avatar', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
