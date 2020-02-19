<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('users', function (Blueprint $table) : void {
            $table->increments('id');

            $table->string('email')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('link')->nullable();

            $table->rememberToken();

            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('users');
    }
}
