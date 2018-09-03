<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogGraphqlOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_graphql_operations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('schema',63)->default(config('graphql.default_schema'));

            $table->enum('type', \App\Enums\GraphQLOperationType::getValues())
                ->default(\App\Enums\GraphQLOperationType::getDefault()->getValue());

            $table->string('operation_name', 255)->nullable();
            $table->text('query');
            $table->jsonb('variables');

            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('client_id')->nullable();
            $table->string('access_token_id', 100)->nullable();

            $table->timestamp('requested_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_graphql_operations');
    }
}
