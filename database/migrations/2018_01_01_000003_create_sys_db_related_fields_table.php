<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateSysDbRelatedFieldsTable
 */
class CreateSysDbRelatedFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_db_related_fields', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('name')->nullable(false)->unique();
            $table->string('type')->nullable(false);
            $table->string('on_delete')->nullable(false);


            $table->unsignedBigInteger('sys_db_source_field_definition_id');
            $table->foreign('sys_db_source_field_definition_id')
                  ->references('id')->on('sys_db_field_definitions')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('sys_db_target_field_definition_id');
            $table->foreign('sys_db_target_field_definition_id')
                  ->references('id')->on('sys_db_field_definitions')
                  ->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_db_related_fields');
    }
}
