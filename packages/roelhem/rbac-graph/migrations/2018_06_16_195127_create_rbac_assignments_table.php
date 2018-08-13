<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRbacAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rbac_assignments', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('node_id');
            $table->morphs('assignable');

            $table->timestamps();

            $table->foreign('node_id')->references('id')->on('rbac_nodes')
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
        Schema::dropIfExists('rbac_assignments');
    }
}
