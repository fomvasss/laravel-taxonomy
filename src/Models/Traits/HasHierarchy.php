<?php

namespace Fomvasss\Taxonomy\Models\Traits;

/**
 * Трейт для получения моделей для дальнейшего
 * формирование иерархического дерева (используется для модели термов)
 * и для формирование значений служебных полей иерархии
 *
 * @package App\Models\Traits
 */
trait HasHierarchy
{
    /**
     * Формирование значений полей иерархии
     * при сохранение/обновлении модели
     *
     * @param $value
     */
    public function setParentIdAttribute($value)
    {
        if ($value) {
            $this->attributes['parent_id'] = $value;
            $parent = self::query()->find($value);
            $this->attributes['root_parent_id'] = $parent->root_parent_id ?? $parent->id;
            $this->attributes['level'] = ++$parent->level;
        }
    }

    /**
     * Все термы по иерархии вверх для полученного терма
     *
     * @param $query
     * @param bool $current
     * @return mixed
     */
    public function scopeHierarchyUp($query, bool $current = false)
    {
        $query->where('root_parent_id', $this->root_parent_id)
            ->where('level', '>', $this->level);

        return $current ? $query->orWhere('id', $this->id) : $query; // + текущий элемент
    }

    /**
     * Все термы по иерархии вниз для полученного терма
     *
     * @param $query
     * @param bool $current
     * @return mixed
     */
    public function scopeHierarchyDown($query, bool $current = false)
    {
        $query->where('root_parent_id', $this->root_parent_id)
            ->where('level', '<', $this->level)
            ->orWhere('id', $this->root_parent_id); // + базовый терм

        return $current ? $query->orWhere('id', $this->id) : $query; // + текущий элемент
    }
}
