<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('title');
            $table->string('short_description');
            $table->text('description');
            $table->smallInteger('number_of_pages')->unsigned();
            $table->tinyInteger('cover_type_id');
            $table->date('published_at');
            $table->decimal('avg_rating', 3, 2);
            $table->bigInteger('rater_count')->unsigned();
            $table->text('cover');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
