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
            $table->boolean('for_user')->default(false);
            $table->boolean('for_group')->default(false);
            $table->boolean('for_group_category')->default(false);

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

        Schema::create('user_role', function (Blueprint $table) {
            $table->integer('user_id');
            $table->string('role_id', 63);

            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'role_id']);
        });

        Schema::create('group_role', function (Blueprint $table) {
            $table->integer('group_id');
            $table->string('role_id', 63);

            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['group_id', 'role_id']);
        });

        Schema::create('group_category_role', function (Blueprint $table) {
            $table->string('group_category_id', 63);
            $table->string('role_id', 63);

            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('group_category_id')->references('id')->on('group_categories')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['group_category_id', 'role_id']);
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
