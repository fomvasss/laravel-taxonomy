<?php

namespace Fomvasss\Taxonomy\Models\Traits;

use Fomvasss\Taxonomy\Models\Term;
use Fomvasss\Taxonomy\Models\Vocabulary;

/**
 * Сущность текущей модели "относится" к разным
 * словарям или термам
 * (для класса модели к которому подключен трейт - используется не для категоризации)
 *
 * Trait HasTaxonomyablesToMany
 *
 * @package App\Models\Traits
 */
trait HasTaxonomyablesToMany
{
    /**
     * Сущность текущей модели "относится" к разным словарям
     * Получить к список словарей, к которым относится
     *
     * @return $this
     */
    public function vocabulariesToMany()
    {
        return $this->morphToMany(Vocabulary::class, 'vocabularyable');
    }

    /**
     * Сущность текущей модели "относится" к разным термам
     * Получить список термов, к которым относится
     *
     * @return $this
     */
    public function termsToMany()
    {
        return $this->morphToMany(Term::class, 'termable');
    }
}
