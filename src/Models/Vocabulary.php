<?php

namespace Fomvasss\Taxonomy\Models;

use Fomvasss\Taxonomy\Models\Traits\HasTaxonomyablesToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vocabulary extends Model
{
    use SoftDeletes,
        HasTaxonomyablesToMany;

    protected $guarded = ['id'];

    public $timestamps = false;

    /**
     * Связь:
     * Словарь имет много термов
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function terms()
    {
        $related = config('taxonomy.models.term', Term::class);
        return $this->hasMany($related);
    }

    /**
     * Связь:
     * Сущность текущей модели (словарь) "держит" много термов
     * (полиморфная связь! - не используется в категоризации)
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
     * Сущность текущей модели "держит" много словарей
     * (полиморфная связь! - не используется в категоризации)
     *
     * @return $this
     */
    public function vocabulariesByMany()
    {
        $related = config('taxonomy.models.vocabulary', Vocabulary::class);
        return $this->morphedByMany($related, 'vocabularyable');
    }
}
