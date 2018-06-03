<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificate_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',255);
            $table->string('name_short', 63)->nullable();
            $table->string('slug', 63)->nullable();
            $table->text('description')->nullable();

            $table->integer('default_expire_years')->nullable();

            $table->boolean('is_required')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certificate_categories');
    }
}
