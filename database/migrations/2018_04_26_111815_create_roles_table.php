<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->string('id', 63);

            $table->string('name',255)->nullable();
            $table->text('description')->nullable();

            $table->boolean('is_required')->default(false);
            $table->boolean('is_visible')->default(false);

            $table->timestamps();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->primary('id');
        });

        Schema::create('role_role', function (Blueprint $table) {
            $table->string('parent_id', 63);
            $table->string('child_id', 63);

            $table->foreign('child_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['parent_id', 'child_id']);
        });

        Schema::create('role_assignments', function(Blueprint $table) {
            $table->increments('id');

            $table->string('role_id', 63);
            $table->morphs('assignable');

            $table->timestamps();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->foreign('role_id')->references('id')->on('roles')
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
        Schema::dropIfExists('roles');

        Schema::dropIfExists('role_role');
        Schema::dropIfExists('user_role');
        Schema::dropIfExists('group_role');
        Schema::dropIfExists('group_category_role');
    }
}
