<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWebfullconceptDatabase extends Migration {

        /**
         * Run the migrations.
         *
         * @return void
         */
         public function up()
         {
            
	    /**
	     * Table: proyecto_comentario_status
	     */
	    Schema::create('proyecto_comentario_status', function($table) {
                $table->integer('id')->unique();
                $table->string('nombre', 150);
                $table->integer('ordering');
            });


	    /**
	     * Table: proyecto_comentarios
	     */
	    Schema::create('proyecto_comentarios', function($table) {
                $table->increments('id')->unsigned();
                $table->integer('proyecto_id')->unsigned();
                $table->text('comentario');
                $table->string('nombre', 150)->nullable();
                $table->boolean('stars')->default("1");
                $table->timestamp('created_at')->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->default("0000-00-00 00:00:00");
                $table->integer('ordering');
                $table->boolean('published')->default("1");
                $table->boolean('removed');
                $table->integer('status_id');
            });


	    /**
	     * Table: proyecto_imagenes
	     */
	    Schema::create('proyecto_imagenes', function($table) {
                $table->increments('id')->unsigned();
                $table->integer('proyecto_id')->unsigned();
                $table->string('archivo', 255);
                $table->string('logo', 255);
                $table->timestamp('created_at')->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->default("0000-00-00 00:00:00");
                $table->integer('ordering');
                $table->boolean('published')->default("1");
                $table->boolean('removed');
            });


	    /**
	     * Table: proyecto_slideshow
	     */
	    Schema::create('proyecto_slideshow', function($table) {
                $table->increments('id')->unsigned();
                $table->integer('proyecto_id')->unsigned();
                $table->string('archivo', 255);
                $table->timestamp('created_at')->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->default("0000-00-00 00:00:00");
                $table->integer('ordering');
                $table->boolean('published')->default("1");
                $table->boolean('removed');
            });


	    /**
	     * Table: proyectos
	     */
	    Schema::create('proyectos', function($table) {
                $table->increments('id')->unsigned();
                $table->string('titulo', 150);
                $table->text('concepto');
                $table->text('descripcion');
                $table->string('logo', 255);
                $table->timestamp('created_at')->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->default("0000-00-00 00:00:00");
                $table->integer('ordering');
                $table->boolean('published')->default("1");
                $table->boolean('removed');
            });


	    /**
	     * Table: user_nivel_acceso
	     */
	    Schema::create('user_nivel_acceso', function($table) {
                $table->increments('id')->unsigned();
                $table->string('tipo', 100);
                $table->string('descripcion', 250);
                $table->boolean('published')->default("1");
            });


	    /**
	     * Table: users
	     */
	    Schema::create('users', function($table) {
                $table->increments('id')->unsigned();
                $table->string('nombre', 100);
                $table->string('ap', 100)->nullable();
                $table->string('am', 100)->nullable();
                $table->enum('genero', array('M','F'));
                $table->string('email', 255)->unique();
                $table->text('direccion')->nullable();
                $table->string('username', 255);
                $table->string('password', 255);
                $table->string('random_key', 30);
                $table->integer('nivel_id')->unsigned();
                $table->enum('status', array('A','I'));
                $table->string('remember_token', 100)->nullable();
                $table->timestamp('created_at')->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->default("0000-00-00 00:00:00");
                $table->boolean('agree');
                //$table->index('email_unique');
            });


         }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
         public function down()
         {
            
	            Schema::drop('proyecto_comentario_status');
	            Schema::drop('proyecto_comentarios');
	            Schema::drop('proyecto_imagenes');
	            Schema::drop('proyecto_slideshow');
	            Schema::drop('proyectos');
	            Schema::drop('user_nivel_acceso');
	            Schema::drop('users');
         }

}