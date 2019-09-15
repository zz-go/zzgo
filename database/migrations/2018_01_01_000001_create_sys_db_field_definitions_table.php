<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysDbFieldDefinitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_db_field_definitions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('sys_db_table_definition_id');
            $table->foreign('sys_db_table_definition_id')
                  ->references('id')->on('sys_db_table_definitions')
                  ->onDelete('cascade');

            $table->string('name')->nullable(false)->unique();
            $table->string('type')->nullable(false);
            $table->boolean('index')->default(false);
            $table->boolean('unsigned')->nullable(true);
            $table->boolean('nullable')->default(false);
            $table->string('default')->nullable(true);
            $table->softDeletes();
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
        Schema::dropIfExists('sys_db_field_definitions');
    }
}