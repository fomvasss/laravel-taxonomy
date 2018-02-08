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
        echo "1. vocabularies table created \n";

        $this->createTermsTable();
        echo "2. terms table created \n";

        $this->createVocabulariablesTable();
        echo "3. vocabulariables table created \n";

        $this->createTermablesTable();
        echo "4. termables table created \n";
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

            $table->timestamps();
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
            $table->text('description')->nullable();
            $table->integer('weight')->default(0);

            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('root_parent_id')->unsigned()->nullable();
            $table->integer('vocabulary_id')->unsigned();
            $table->integer('level')->default(1);

            $table->timestamps();

            $table->softDeletes();
            $table->foreign('vocabulary_id')->references('id')->on('vocabularies')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('parent_id')->references('id')->on('terms')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('root_parent_id')->references('id')->on('terms')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Таблица сущностей "привязанных" к словарям
     */
    public function createVocabulariablesTable()
    {
        Schema::create('vocabularyables', function (Blueprint $table) {
            $table->integer('vocabulary_id')->unsigned();
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
             $table->integer('term_id')->unsigned();
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
