<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRbacEdgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rbac_edges', function (Blueprint $table) {
            $table->unsignedInteger('parent_id');
            $table->unsignedInteger('child_id');

            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('rbac_nodes')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('child_id')->references('id')->on('rbac_nodes')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->primary(['parent_id','child_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rbac_edges');
    }
}
