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
        $related = config('taxonomy.models.vocabulary', Vocabulary::class);
        return $this->morphToMany($related, 'vocabularyable');
    }

    /**
     * Сущность текущей модели "относится" к разным термам
     * Получить список термов, к которым относится
     *
     * @return $this
     */
    public function termsToMany()
    {
        $related = config('taxonomy.models.term', Term::class);
        return $this->morphToMany($related, 'termable');
    }
}
