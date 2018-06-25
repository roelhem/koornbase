<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRbacPathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rbac_paths', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('first_node_id');
            $table->unsignedInteger('last_node_id');

            $table->unsignedInteger('first_path_id')->nullable();
            $table->unsignedInteger('last_path_id')->nullable();

            $table->integer('size')->default(0);
            $table->jsonb('path')->default('[]')->unique();

            $table->foreign('first_node_id')->references('id')->on('rbac_nodes')
                ->onUpdate('restrict')->onDelete('cascade');
            $table->foreign('last_node_id')->references('id')->on('rbac_nodes')
                ->onUpdate('restrict')->onDelete('cascade');

            $table->foreign('first_path_id')->references('id')->on('rbac_paths')
                ->onUpdate('restrict')->onDelete('cascade');
            $table->foreign('last_path_id')->references('id')->on('rbac_paths')
                ->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rbac_paths');
    }
}
