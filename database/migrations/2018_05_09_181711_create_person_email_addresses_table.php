<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonEmailAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_email_addresses', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('person_id');
            $table->integer('index');
            $table->string('label',63);
            $table->jsonb('options')->default('{}');

            $table->string('email_address', 255);

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
        Schema::dropIfExists('person_email_addresses');
    }
}
