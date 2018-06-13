<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxonomiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createVocabulariesTable();
        $this->createTermsTable();
        $this->createVocabulariablesTable();
        $this->createTermablesTable();
    }

    /**
     * Таблица словарей
     */
    public function createVocabulariesTable()
    {
        Schema::create('vocabularies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('system_name')->unique();
            $table->string('name');
            $table->text('description')->nullable();

            $table->softDeletes();
        });
    }

    /**
     * Таблица термов
     */
    public function createTermsTable()
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
//            $table->string('slug')->nullable()->unique();
//            $table->string('system_name')->nullable()->unique();
            $table->text('description')->nullable();
            $table->integer('weight')->default(0);
//            $table->nestedSet(); // _lft, _rgt, parent_id - only if you use "lazychaser/laravel-nestedset"
            $table->unsignedInteger('vocabulary_id');
            $table->string('type'); //This is vocabulary system name
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Таблица сущностей "привязанных" к словарям
     */
    public function createVocabulariablesTable()
    {
        Schema::create('vocabularyables', function (Blueprint $table) {
            $table->unsignedInteger('vocabulary_id');
            $table->morphs('vocabularyable');

            $table->foreign('vocabulary_id')->references('id')->on('vocabularies')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Таблица сущностей "привязанных" к термам
     */
    public function createTermablesTable()
    {
         Schema::create('termables', function (Blueprint $table) {
             $table->unsignedInteger('term_id');
             $table->morphs('termable');
    
             $table->foreign('term_id')->references('id')->on('terms')->onDelete('CASCADE')->onUpdate('CASCADE');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('termables');
        Schema::dropIfExists('vocabularyables');
        Schema::dropIfExists('terms');
        Schema::dropIfExists('vocabularies');
    }
}
