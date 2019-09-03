<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCardsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_cards_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('type');
            $table->float('amount', '12', '2');
            $table->string('recipient_card_number')->nullable();
            $table->unsignedBigInteger('user_card_id');
            $table->foreign('user_card_id')->references('id')
                ->on('user_cards')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_cards_history', function (Blueprint $table) {
            $table->dropForeign(['user_card_id']);
        });
        Schema::dropIfExists('user_cards_history');
    }
}
