<?php

namespace Fomvasss\Taxonomy\Models;

use Fomvasss\Taxonomy\Models\Traits\HasHierarchy;
use Fomvasss\Taxonomy\Models\Traits\HasTaxonomies;
use Fomvasss\Taxonomy\Models\Traits\HasTaxonomyablesToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Term extends Model
{
    use SoftDeletes,
        HasTaxonomies,
        HasTaxonomyablesToMany,
        HasHierarchy;

    protected $guarded = ['id'];

    /**
     * Связь:
     * Терм пренадлежит (относится к) одному словарю
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vocabulary()
    {
        $related = config('taxonomy.models.vocabulary', Vocabulary::class);
        return $this->belongsTo($related);
    }

    /**
     * Связь:
     * Сущность текущей модели "держит" много термов
     *
     * @return $this
     */
    public function termsByMany()
    {
        $related = config('taxonomy.models.term', Term::class);
        return $this->morphedByMany($related, 'termable');
    }

    /**
     * Связь:
     * Сущность текущей модели "держит" много словарей
     *
     * @return $this
     */
    public function vocabulariesByMany()
    {
        $related = config('taxonomy.models.vocabulary', Vocabulary::class);
        return $this->morphedByMany($related, 'termable');
    }

    /**
     * Все термы по указанным словарям
     *
     * @param $query
     * @param $vocabulary
     * @param string|null $vocabularyKey
     * @return mixed
     */
    public function scopeByVocabulary($query, $vocabulary, string $vocabularyKey = 'system_name')
    {
        if ($vocabularyKey == 'id') {
            return $query->where('vocabulary_id', $vocabulary);
        }

        return $query->whereHas('vocabulary', function ($q) use ($vocabulary, $vocabularyKey) {
            $q->where($vocabularyKey, $vocabulary);
        });
    }

    /**
     * TODO пример!
     *
     * Связь:
     * Терм "держит" много статтей
     * Получить список статтей
     * Методы описываются в переопределенной модели Term!
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function postsExample()
    {
        return $this->morphedByMany(PostExample::class, 'termable');
    }
}
