<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('subscription_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->enum('type', ['free','paid','trial','custom'])->default('free');
            $table->string('validity')->nullable();
            $table->date('exp_date')->nullable();
            $table->decimal('price', 12, 2)->default(0.00);
            $table->integer('max_daily_limit')->default(0);
            $table->integer('max_limit')->default(0);
            $table->integer('total_subscriber')->default(0);
            $table->integer('active_subscriber')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->index(['is_active','is_deleted']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscription_packages');
    }
};
