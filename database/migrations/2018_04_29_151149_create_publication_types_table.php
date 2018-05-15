<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publication_types', function (Blueprint $table) {
            $table->string('id', 63);


            $table->string('name', 255);
            $table->string('name_short', 63)->nullable();
            $table->text('description')->nullable();

            $table->jsonb('structure');

            $table->string('default_name')->nullable();
            $table->jsonb('default_data')->nullable();

            $table->boolean('is_required')->default(false);

            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();

            $table->primary(['id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publication_types');
    }
}
