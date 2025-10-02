<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create USERS as a REFERENCE TABLE (globally replicated)
        // This allows the UNIQUE constraint on email
        DB::statement("
            CREATE REFERENCE TABLE users (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                email_verified_at TIMESTAMP NULL,
                password VARCHAR(255) NOT NULL,
                remember_token VARCHAR(100) NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )
        ");

        // PASSWORD RESET TOKENS: also reference table (small and global)
        DB::statement("
            CREATE REFERENCE TABLE password_reset_tokens (
                email VARCHAR(255) PRIMARY KEY,
                token VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NULL
            )
        ");

        // SESSIONS table: can stay as a normal sharded table
        Schema::create('sessions', function ($table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        DB::statement("DROP TABLE IF EXISTS password_reset_tokens");
        DB::statement("DROP TABLE IF EXISTS users");
    }
};
