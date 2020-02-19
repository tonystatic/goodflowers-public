<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('socials', function (Blueprint $table) : void {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();

            $table->string('provider', 10)->nullable();
            $table->string('ext_id')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('link')->nullable();

            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();

            $table->index(['provider', 'ext_id']);
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
        Schema::dropIfExists('socials');
    }
}
