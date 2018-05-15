<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->increments('id');

            $table->string('slug', 63)->unique();
            $table->string('name', 255);
            $table->string('name_short', 63)->nullable();
            $table->text('description')->nullable();

            $table->date('start')->nullable();
            $table->date('end')->nullable();

            $table->decimal('amount', 12, 2)->nullable();

            $table->unsignedBigInteger('exact_ref')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
        });

        Schema::create('budget_expanse_category', function(Blueprint $table) {
           $table->unsignedInteger('budget_id');
           $table->unsignedInteger('category_id');

           $table->boolean('is_default')->default(false);

            $table->foreign('budget_id')->references('id')->on('budgets')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('expanse_categories')
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
        Schema::dropIfExists('budgets');

        Schema::dropIfExists('budget_expanse_category');
    }
}
