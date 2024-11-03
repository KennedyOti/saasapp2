<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppsTable extends Migration
{
    public function up()
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->string('app_name', 50);
            $table->text('description')->nullable();
            $table->enum('status', ['private', 'public'])->default('private'); // Status field for private/public
            $table->timestamps();

            $table->unique(['id']);
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('apps');
    }
}
