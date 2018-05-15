<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');

            $table->string('category_id', 63);
            $table->unsignedInteger('venue_id')->nullable();
            $table->unsignedInteger('manager_id')->nullable();
            $table->unsignedInteger('debtor_id')->nullable();

            $table->string('slug',63)->unique();
            $table->string('name',255);
            $table->text('description')->nullable();

            $table->timestamp('start');
            $table->timestamp('end');

            $table->boolean('is_open')->default(false);
            $table->unsignedBigInteger('facebook_event')->nullable();
            $table->string('google_event', 255)->nullable();
            $table->smallInteger('visibility')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();

            $table->foreign('category_id')->references('id')->on('event_categories')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('venue_id')->references('id')->on('venues')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('manager_id')->references('id')->on('persons')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('debtor_id')->references('id')->on('debtors')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
