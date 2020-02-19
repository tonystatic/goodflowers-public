<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('donations', function (Blueprint $table) : void {
            $table->increments('id');
            $table->string('hash_id', 15)->nullable()->collation('utf8mb4_bin')->index();

            $table->unsignedInteger('garden_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();

            $table->boolean('complete')->default(false)->index();
            $table->unsignedBigInteger('amount')->default(0);
            $table->unsignedInteger('flowers_quantity')->default(1);

            $table->timestamps();

            $table->foreign('garden_id')
                ->references('id')->on('gardens')
                ->onDelete('set null');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('donations');
    }
}
