<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsTable extends Migration
{
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('app_id')->constrained()->onDelete('cascade');
            $table->string('form_name', 50);
            $table->string('form_description', 100)->nullable();
            $table->unsignedBigInteger('child_form_id')->nullable();
            $table->string('default_form_style', 5)->default('Table');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['id']);
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('forms');
    }
}
