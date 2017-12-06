<?php

namespace Fomvasss\Taxonomy\Models;

use Fomvasss\Taxonomy\Models\Traits\HasTaxonomyablesToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vocabulary extends Model
{
    use SoftDeletes,
        HasTaxonomyablesToMany;

    protected $fillable = [
        'name',
        'description',
        'body',
    ];

    /**
     * Связь:
     * Словарь имет много термов
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function terms()
    {
        return $this->hasMany(Term::class);
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
        return $this->morphedByMany(Term::class, 'vocabularyable');
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
        return $this->morphedByMany(Vocabulary::class, 'vocabularyable');
    }
}
