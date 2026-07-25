<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // New landlords must be approved by an admin before they can list.
            $table->boolean('is_approved')->default(false)->after('role');
        });

        // Existing accounts predate approval — grant them access.
        DB::table('users')->update(['is_approved' => true]);

        // Admins are always approved.
        DB::table('users')->where('role', 'admin')->update(['is_approved' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });
    }
};
