<?php

namespace Fomvasss\Taxonomy\Models;

use Fomvasss\Taxonomy\Models\Traits\HasTaxonomyablesToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vocabulary extends Model
{
    use HasTaxonomyablesToMany;

    protected $guarded = ['id'];

    public $timestamps = false;

    /**
     * Связь:
     * Словарь имеет много термов
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function terms()
    {
        $related = config('taxonomy.models.term', Term::class);
        
        return $this->hasMany($related, 'vocabulary');
    }

    /**
     * Связь:
     * Сущность текущей модели - словарь "держит" много термов
     * (полиморфная связь! - не используется в этом пакете для категоризации)
     *
     * @return $this
     */
    public function termsByMany()
    {
        $related = config('taxonomy.models.term', Term::class);
        
        return $this->morphedByMany($related, 'vocabularyable');
    }

    /**
     * Связь:
     * Сущность текущей модели - словарь "держит" много словарей
     * (полиморфная связь! - не используется в этом пакете для категоризации)
     *
     * @return $this
     */
    public function vocabulariesByMany()
    {
        $related = config('taxonomy.models.vocabulary', static::class);
        
        return $this->morphedByMany($related, 'vocabularyable');
    }
}
