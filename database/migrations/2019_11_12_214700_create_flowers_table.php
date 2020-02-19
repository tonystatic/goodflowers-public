<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlowersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('flowers', function (Blueprint $table) : void {
            $table->increments('id');
            $table->string('hash_id', 15)->nullable()->collation('utf8mb4_bin')->index();
            $table->unsignedInteger('donation_id')->nullable();

            $table->unsignedSmallInteger('color')->nullable();
            $table->string('shape', 10)->nullable();

            $table->string('file_path')->nullable();

            $table->timestamps();

            $table->foreign('donation_id')
                ->references('id')->on('donations')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('flowers');
    }
}
