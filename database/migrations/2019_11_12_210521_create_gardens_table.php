<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGardensTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('gardens', function (Blueprint $table) : void {
            $table->increments('id');
            $table->string('hash_id', 15)->nullable()->collation('utf8mb4_bin')->index();

            $table->string('slug')->nullable()->index();

            $table->string('owner_name')->nullable();
            $table->string('owner_link')->nullable();
            $table->string('owner_avatar_path')->nullable();

            $table->unsignedBigInteger('total_value')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('gardens');
    }
}
