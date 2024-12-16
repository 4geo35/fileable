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
        Schema::create('files', function (Blueprint $table) {
            $table->id();

            $table->string("path")
                ->comment("Путь к файлу");

            $table->string("name")
                ->comment("Имя файла");

            $table->string("mime")
                ->nullable()
                ->comment("Расширение файла");

            $table->unsignedBigInteger("priority")
                ->default(0)
                ->comment("Приоритет вывода");

            $table->string("type")
                ->default("file")
                ->comment("Тип файла");

            $table->unsignedBigInteger("parent_id")
                ->nullable()
                ->comment("Ссылка на оригинал");

            $table->string("template")
                ->nullable()
                ->comment("Стиль миниатюры");

            $table->nullableMorphs("fileable");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
