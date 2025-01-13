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
        Schema::create('thumb_images', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("image_id")
                ->nullable()
                ->comment("Ссылка на оригинал");

            $table->string("path")
                ->comment("Путь к файлу");

            $table->string("name")
                ->comment("Имя файла");

            $table->string("mime")
                ->nullable()
                ->comment("Расширение файла");

            $table->string("template")
                ->nullable()
                ->comment("Стиль миниатюры");

            $table->timestamps();
        });

        Schema::table("files", function (Blueprint $table) {
            $table->dropColumn("parent_id");
            $table->dropColumn("template");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thumb_images');

        Schema::table("files", function (Blueprint $table) {
            $table->unsignedBigInteger("parent_id")
                ->nullable()
                ->after("type")
                ->comment("Ссылка на оригинал");

            $table->string("template")
                ->nullable()
                ->after("parent_id")
                ->comment("Стиль миниатюры");
        });
    }
};
