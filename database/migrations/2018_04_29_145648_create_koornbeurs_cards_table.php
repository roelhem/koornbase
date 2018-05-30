<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKoornbeursCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('koornbeurs_cards', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('owner_id')->nullable();

            $table->string('ref', 63)->nullable();
            $table->string('version', 63)->nullable();


            $table->timestamp('activated_at')->nullable();
            $table->timestamp('deactivated_at')->nullable();

            $table->unsignedInteger('activated_by')->nullable();
            $table->unsignedInteger('deactivated_by')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->foreign('owner_id')->references('id')->on('persons')
                ->onUpdate('cascade')->onDelete('set null');

            $table->unique(['ref','version']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('koornbeurs_cards');

        Schema::dropIfExists('person_koornbeurs_card');
    }
}
