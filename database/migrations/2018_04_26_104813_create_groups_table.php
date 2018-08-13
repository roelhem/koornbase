<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('category_id');

            $table->string('slug', 63)->unique();

            $table->string('name',255);
            $table->string('name_short', 63)->nullable();
            $table->text('description')->nullable();

            $table->string('member_name', 255)->nullable();

            $table->boolean('is_required')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();

            $table->foreign('category_id')->references('id')->on('group_categories')
                ->onUpdate('cascade')->onDelete('restrict');
        });

        Schema::create('person_group', function(Blueprint $table) {
            $table->unsignedInteger('person_id');
            $table->unsignedInteger('group_id');

            $table->primary(['person_id','group_id']);

            $table->foreign('person_id')->references('id')->on('persons')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('group_id')->references('id')->on('groups')
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
        Schema::dropIfExists('groups');

        Schema::dropIfExists('person_group');
    }
}
