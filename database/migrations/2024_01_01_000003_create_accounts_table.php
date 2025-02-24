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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->unsignedBigInteger('bakery_id')->nullable();
            $table->decimal('balance', 10, 2)->default(0);
            $table->boolean('is_general')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('bakery_id')->references('id')->on('bakeries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
