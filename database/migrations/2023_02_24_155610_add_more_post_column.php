<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->datetime('publish_at')->nullable();
            $table->datetime('publish_until')->nullable();
            $table->boolean('is_published')->default(false);
            $table->string('category')->nullable();
            $table->foreignIdFor(\App\Models\User::class)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->removeColumn('publish_at');
            $table->removeColumn('publish_until');
            $table->removeColumn('is_published');
            $table->removeColumn('category');
            $table->removeColumn('user_id');
        });
    }
};
