<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonPhoneNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_phone_numbers', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('person_id');

            $table->string('label',63);

            $table->boolean('is_primary')->default(false);
            $table->boolean('for_emergency')->default(false);

            $table->boolean('is_mobile')->default(false);

            $table->string('phone_number', 63);

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->foreign('person_id')->references('id')->on('persons')
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
        Schema::dropIfExists('person_phone_numbers');
    }
}
