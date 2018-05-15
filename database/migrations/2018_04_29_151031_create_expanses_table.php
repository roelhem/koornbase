<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpansesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expanses', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('event_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('budget_id')->nullable();

            $table->string('name', 255);
            $table->text('description')->nullable();

            $table->decimal('amount_expected', 12, 2)->nullable();
            $table->decimal('amount_real', 12, 2)->nullable();
            $table->decimal('amount_deposited', 12, 2)->nullable();
            $table->decimal('amount_reimbursed', 12, 2)->nullable();

            $table->unsignedBigInteger('exact_ref')->nullable();

            $table->timestamps();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->foreign('event_id')->references('id')->on('events')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('expanse_categories')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('budget_id')->references('id')->on('budgets')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expanses');
    }
}
