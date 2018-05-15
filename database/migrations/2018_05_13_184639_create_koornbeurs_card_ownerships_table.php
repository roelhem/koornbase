<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKoornbeursCardOwnershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('koornbeurs_card_ownerships', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('person_id');
            $table->unsignedInteger('card_id');

            $table->date('start')->nullable();
            $table->date('end')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->foreign('person_id')->references('id')->on('persons')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('card_id')->references('id')->on('koornbeurs_cards')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('koornbeurs_card_ownerships');
    }
}
