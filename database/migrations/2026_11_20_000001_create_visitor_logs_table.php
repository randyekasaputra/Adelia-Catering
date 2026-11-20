<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('visitor_logs')) {
            Schema::create('visitor_logs', function (Blueprint $table) {
                $table->id();
                $table->string('session_id', 100)->nullable()->index();
                $table->string('ip_address', 45)->nullable()->index();
                $table->string('url_path', 255)->default('/');
                $table->string('device_type', 30)->default('Desktop')->index(); // Mobile, Desktop, Tablet
                $table->string('os', 50)->default('Lainnya'); // Android, iOS, Windows, macOS, Linux
                $table->string('browser', 50)->default('Lainnya'); // Chrome, Safari, Edge, Firefox, Opera
                $table->text('user_agent')->nullable();
                $table->date('visited_date')->index();
                $table->timestamps();

                $table->index(['visited_date', 'session_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
