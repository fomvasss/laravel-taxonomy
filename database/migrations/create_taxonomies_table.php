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
        $this->crateVocabulariesTable();
        echo "1. vocabularies table created \n";

        $this->crateTermsTable();
        echo "2. terms table created \n";

        $this->crateVocabulariablesTable();
        echo "3. vocabulariables table created \n";

        $this->crateTermablesTable();
        echo "4. termables table created \n";

    }

    /**
     * Таблица словарей
     */
    public function crateVocabulariesTable()
    {
        Schema::create('vocabularies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('system_name')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('body')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Таблица термов
     */
    public function crateTermsTable()
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('body')->nullable();
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
    public function crateVocabulariablesTable()
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
    public function crateTermablesTable()
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
