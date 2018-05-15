<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_addresses', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('person_id');

            $table->string('label',63);

            $table->boolean('is_primary')->default(false);
            $table->boolean('for_emergency')->default(false);

            $table->char('country_code', 2)->default('NL');
            $table->string('administrative_area')->nullable();
            $table->string('locality')->nullable();
            $table->string('dependent_locality')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('sorting_code')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('organisation')->nullable();
            $table->string('locale', 5)->default('und');

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
        Schema::dropIfExists('person_addresses');
    }
}
