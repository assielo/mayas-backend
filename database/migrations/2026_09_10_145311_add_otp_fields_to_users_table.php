<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telephone')->unique()->after('email')->nullable();
            $table->string('otp_code', 6)->nullable()->after('telephone');
            $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
            
            // On rend l'email et le mot de passe optionnels si l'OTP est le seul moyen de connexion
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['telephone', 'otp_code', 'otp_expires_at']);
        });
    }
};
