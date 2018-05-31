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
            $table->integer('index');
            $table->string('label',63);
            $table->jsonb('options')->default('{}');

            $table->string('phone_number', 63);
            $table->char('country_code', 2)->default('NL');

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
