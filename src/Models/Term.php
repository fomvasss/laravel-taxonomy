<?php

namespace Fomvasss\Taxonomy\Models;

use Fomvasss\Taxonomy\Models\Traits\HasTaxonomies;
use Fomvasss\Taxonomy\Models\Traits\HasTaxonomyablesToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class Term extends Model
{
    use HasTaxonomies,
        HasTaxonomyablesToMany,
        NodeTrait;

    protected $guarded = ['id'];
    
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Связь:
     * Терм пренадлежит (относится к) одному словарю
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function txVocabulary()
    {
        $related = config('taxonomy.models.vocabulary', Vocabulary::class);

        return $this->belongsTo($related, 'vocabulary', 'system_name');
    }

    /**
     * Связь:
     * Сущность текущей модели терм "держит" много термов
     *
     * @return $this
     */
    public function termsByMany()
    {
        $related = config('taxonomy.models.term', static::class);
        
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
    public function scopeByVocabulary($query, $vocabulary)
    {
        return $query->where('vocabulary', $vocabulary);
    }
}
