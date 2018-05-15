<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevenueCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revenue_categories', function (Blueprint $table) {
            $table->increments('id');

            $table->string('slug', 63)->unique();
            $table->string('name', 255);
            $table->string('name_short', 63)->nullable();
            $table->text('description')->nullable();

            $table->unsignedBigInteger('exact_ref')->nullable();

            $table->decimal('effective_treshold', 12, 2)->nullable();
            $table->decimal('effective_factor', 12, 2)->nullable();

            $table->string('default_name', 255)->nullable();
            $table->decimal('default_amount_expected', 12, 2)->nullable();

            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('revenue_categories');
    }
}
