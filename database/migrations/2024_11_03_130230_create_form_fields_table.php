<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormFieldsTable extends Migration
{
    public function up()
    {
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('app_id')->constrained()->onDelete('cascade');
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->string('field_name', 50);
            $table->string('field_description', 100)->nullable();
            $table->string('mouse_hover_tip', 100)->nullable();
            $table->string('field_type', 10);
            $table->unsignedInteger('field_size')->nullable();
            $table->unsignedInteger('decimals')->nullable();
            $table->string('field_format', 10)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['id']);
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('form_fields');
    }
}
