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
        Schema::create('permission_denies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('role_name')->nullable()->index();
            $table->string('permission'); // e.g. post.edit
            $table->string('resource_type')->nullable(); // Post, Comment
            $table->unsignedBigInteger('resource_id')->nullable();
            $table->string('reason')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_denies');
    }
};
