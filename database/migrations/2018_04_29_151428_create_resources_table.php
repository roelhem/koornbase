<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->increments('id');

            $table->string('category_id', 63);
            $table->unsignedInteger('parent_id')->nullable();

            $table->string('slug', 63)->unique();
            $table->string('name', 255);
            $table->string('name_short', 63)->nullable();
            $table->text('description')->nullable();

            $table->jsonb('specs')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();

            $table->foreign('category_id')->references('id')->on('resource_categories')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('parent_id')->references('id')->on('resources')
                ->onUpdate('cascade')->onDelete('set null');
        });

        Schema::create('event_resource', function(Blueprint $table) {
            $table->unsignedInteger('event_id');
            $table->unsignedInteger('resource_id');

            $table->timestamp('reserved_at')->nullable();
            $table->unsignedInteger('reserved_by')->nullable();

            $table->timestamp('confirmed_at')->nullable();
            $table->unsignedInteger('confirmed_by')->nullable();

            $table->foreign('event_id')->references('id')->on('events')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('resource_id')->references('id')->on('resources')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('room_resource', function(Blueprint $table) {
            $table->unsignedInteger('room_id');
            $table->unsignedInteger('resource_id');

            $table->smallInteger('availability')->default(1);

            $table->foreign('room_id')->references('id')->on('rooms')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('resource_id')->references('id')->on('resources')
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
        Schema::dropIfExists('resources');

        Schema::dropIfExists('event_resource');
        Schema::dropIfExists('room_resource');
    }
}
