<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('menu_items', 'image_data_url')) {
            return;
        }

        Schema::table('menu_items', function (Blueprint $table) {
            $table->text('image_data_url')->nullable()->after('image_url');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('menu_items', 'image_data_url')) {
            return;
        }

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('image_data_url');
        });
    }
};
