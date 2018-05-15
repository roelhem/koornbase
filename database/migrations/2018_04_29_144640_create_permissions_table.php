<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {

            $table->string('id', 63);

            $table->string('name',255)->nullable();
            $table->text('description')->nullable();
            $table->primary('id');
        });

        Schema::create('permission_permission', function (Blueprint $table) {
            $table->string('parent_id', 63);
            $table->string('child_id', 63);

            $table->foreign('child_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['parent_id', 'child_id']);
        });

        Schema::create('role_permission', function (Blueprint $table) {
            $table->string('role_id', 63);
            $table->string('permission_id', 63);

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['role_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');

        Schema::dropIfExists('permission_permission');
        Schema::dropIfExists('role_permission');
    }
}
