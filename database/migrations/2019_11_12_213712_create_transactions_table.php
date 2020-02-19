<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('transactions', function (Blueprint $table) : void {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->nullable();

            $table->string('ext_id')->nullable()->index();
            $table->boolean('successful')->default(true)->index();
            $table->string('type', 10)->nullable()->index();
            $table->nullableMorphs('transactable');

            $table->unsignedInteger('amount')->default(0);
            $table->string('currency', 5)->nullable();

            $table->string('card_type', 50)->nullable();
            $table->unsignedSmallInteger('card_digits')->nullable();
            $table->unsignedTinyInteger('card_exp_month')->nullable();
            $table->unsignedSmallInteger('card_exp_year')->nullable();
            $table->string('card_token')->nullable();

            $table->string('cardholder_name')->nullable();
            $table->string('cardholder_email')->nullable();

            $table->string('error_code')->nullable();

            $table->timestamp('made_at')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('transactions');
    }
}
