<?php

namespace Fomvasss\Taxonomy\Models\Traits;

use Fomvasss\Taxonomy\Models\Term;

/**
 * Трейт для классов-моделей с таксономией
 *
 * Trait HasTaxonomies
 *
 * @package App\Models\Traits
 */
trait HasTaxonomies
{
    /**
     * Связь:
     * Сущность текущей модели "относится" к разным термам
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function terms()
    {
        return $this->morphToMany(Term::class, 'termable');
    }

    /**
     * Связь текущей модели с термамы по указанному словарю (по умолчанию ключ словаря - system_name)
     * Используется для создание связей с термамы нужного словаря в своих моделях
     *
     * @param $vocabulary
     * @param string|null $vocabularyKey
     * @return mixed
     */
    public function termsByVocabulary($vocabulary, string $vocabularyKey = 'system_name')
    {
        return $this->terms()->whereHas('vocabulary', function ($q) use ($vocabulary, $vocabularyKey) {
            $q->where($vocabularyKey, $vocabulary);
        });
    }

    /**
     * Термы текущей модели по указанному словарю
     *
     * @param $query
     * @param $vocabulary
     * @param string $vocabularyKey
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function scopeTermsByVocabulary($query, $vocabulary, string $vocabularyKey = 'system_name')
    {
        return $query->whereHas('terms.vocabulary', function ($v) use ($vocabulary, $vocabularyKey) {
            $v->where($vocabularyKey, $vocabulary);
        });
    }

    /**
     * Сущности по указанным термам с соответствующих
     * указанных словарей, например:
     * получить все статьи терма-категории "WEB-Программирование" с словаря "Категории"
     *
     * @param $query
     * @param array $taxonomies Например (для наглядности в примере slug-и):
     *  [
     *      'regions' => ['moscow', 'rostov'],
     *      'categories' => 'auto-remont'
     *  ]
     * @param string $termKey
     * @param string $vocabularyKey
     * @return mixed
     */
    public function scopeByTaxonomies($query, array $taxonomies, string $termKey = 'id', string $vocabularyKey = 'system_name')
    {
        foreach ($taxonomies as $vocabulary => $terms) {
            $terms = is_array($terms) ? $terms : [$terms];
            if (! empty($terms)) {
                $query->whereHas('terms', function ($t) use ($vocabulary, $terms, $termKey, $vocabularyKey) {
                    $t->whereHas('vocabulary', function ($v) use ($vocabulary, $terms, $termKey, $vocabularyKey) {
                        $v->where($vocabularyKey, $vocabulary);
                    })->whereIn($termKey, $terms);
                });
            }
        }

        return $query;
    }
}
