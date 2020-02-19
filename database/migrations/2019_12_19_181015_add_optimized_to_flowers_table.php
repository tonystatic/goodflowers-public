<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOptimizedToFlowersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::table('flowers', function (Blueprint $table) : void {
            $table->boolean('optimized')->default(false)->index()->after('file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::table('flowers', function (Blueprint $table) : void {
            $table->dropColumn('optimized');
        });
    }
}
