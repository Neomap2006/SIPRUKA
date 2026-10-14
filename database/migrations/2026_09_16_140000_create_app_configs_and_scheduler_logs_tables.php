<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('app_configs')) {
            Schema::create('app_configs', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique()->index();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('scheduler_logs')) {
            Schema::create('scheduler_logs', function (Blueprint $table) {
                $table->id();
                $table->string('type')->default('TEST'); // TEST, DAILY_SCHEDULER, NOTIFICATION
                $table->string('recipient');
                $table->text('message');
                $table->enum('status', ['SUCCESS', 'FAILED'])->default('SUCCESS');
                $table->text('response_details')->nullable();
                $table->timestamp('executed_at')->useCurrent();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('scheduler_logs');
        Schema::dropIfExists('app_configs');
    }
};
