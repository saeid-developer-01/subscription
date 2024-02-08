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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->integer('duration_day')->default(-1);
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('discount_percent')->default(0);
            $table->string('sku_code')->unique();
            $table->string('type');
            $table->integer('count')->default(0)->comment("just for show to Front");
            $table->json('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
