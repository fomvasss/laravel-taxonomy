<?php

namespace Fomvasss\Taxonomy\Models\Traits;

use Fomvasss\Taxonomy\Models\Term;

/**
 * Трейт для пользовательских классов-моделей котории имею таксономию (статьи, товары,...)
 *
 * Trait HasTaxonomies
 *
 * @package App\Models\Traits
 */
trait HasTaxonomies
{
    /**
     * Связь:
     * Сущность текущей модели "относится" к разным термам.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function terms()
    {
        $related = config('taxonomy.models.term', Term::class);

        return $this->morphToMany($related, 'termable');
    }
    
    /**
     * Связь текущей модели с термамы по указанному system_name словаря.
     * Используется для создание связей с термамы нужного словаря в пользовательских моделях.
     *
     * @param $vocabulary
     * @param string|null $vocabularyKey
     * @return mixed
     */
    public function termsByVocabulary($vocabulary)
    {
        return $this->terms()->where('vocabulary', $vocabulary);
    }

    /**
     * Термы текущей модели по указанному system_name словаря.
     *
     * @param $query
     * @param $vocabulary
     * @param string $vocabularyKey
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function scopeTermsByVocabulary($query, $vocabulary)
    {
        return $query->whereHas('terms', function ($t) use ($vocabulary) {
            $t->where('vocabulary', $vocabulary);
        });
    }

    /**
     * Сущности по указанным термам с соответствующих
     * указанных словарей, например:
     * получить все Статьи терма-категории "WEB-Программирование" (23) с словаря "Категории статтей" И 
     * терма-города "Киев" (35) или "Москва" (56) с словаря "Города"
     *
     * @param $query
     * @param array $taxonomies Например:
     *  [
     *      'article_categories' => 23,
     *      'cities' => [35, 56]
     *  ]
     * @param string $termKey
     * @param string $vocabularyKey
     * @return mixed
     */
    public function scopeByTaxonomies($query, array $taxonomies, string $termKey = 'id')
    {
        foreach ($taxonomies as $vocabulary => $terms) {
            $terms = is_array($terms) ? $terms : [$terms];
            if (! empty($terms)) {
                $query->whereHas('terms', function ($t) use ($vocabulary, $terms, $termKey) {
                    $t->where('vocabulary', $vocabulary)->whereIn($termKey, $terms);
                });
            }
        }

        return $query;
    }


    /**
     * Связь:
     * Сущность текущая модель "имеет" один терм.
     * Ключ для связи находится в таблице сущности (Ex.: articles.term_id)
     *
     * @param null $foreignKey
     * @param null $ownerKey
     * @return mixed
     */
    public function term($foreignKey = null, $ownerKey = null, $relation = null)
    {
        $related = config('taxonomy.models.term', Term::class);

        return $this->belongsTo($related, $foreignKey, $ownerKey, $relation);
    }
}
