<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstraintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constraints', function (Blueprint $table) {

            $table->string('id', 63);
            $table->string('name', 255)->nullable();
            $table->text('description')->nullable();

            $table->primary('id');
        });

        Schema::create('permission_constraint', function (Blueprint $table) {

            $table->increments('id');

            $table->string('permission_id', 63);
            $table->string('constraint_id', 63);

            $table->jsonb('params')->nullable();

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('constraint_id')->references('id')->on('constraints')
                ->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_constraint');

        Schema::dropIfExists('constraints');
    }
}
