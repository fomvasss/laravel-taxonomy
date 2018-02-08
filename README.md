# Laravel Taxonomy
Создание и управление таксономиями в Laravel

## Установка

Запустить:
```bash
	composer require fomvasss/laravel-taxonomy
```

Для Laravel > 5.5 добавить в сервис-провайдер в app.php
```php
<?php
	Fomvasss\Taxonomy\TaxonomyServiceProvider::class,
```

## Публикация ресурсов
```bash
	php artisan vendor:publish --provider="Fomvasss\Taxonomy\TaxonomyServiceProvider"
```

```bash
	php artisan migrate
	php artisan db:seed --class=TaxonomyTableSeeder
```

__При использовании опубликованных кастомных моделей, нужно указать в конфигу `taxonomy.php` namespase этих моделей, иначе связи будут работать не корректно__

---
## Использование

### Методы в модели Vocabulary
#### Связи
- terms() - Словарь имет много термов
#### Полиморные связи
- vocabulariesByMany() - Сущность текущей модели "держит" много словарей
- vocabulariesToMany() - Сущность текущей модели "относится" к разным словарям
- termsByMany() - Сущность текущей модели "держит" много термов
- termsToMany() - Сущность текущей модели "относится" к разным термам

---
### Методи в модели Term
#### Связи
- vocabulary() - Терм "пренадлежит" одному словарю
#### Полиморные связи
- vocabulariesByMany() - Сущность текущей модели "держит" много словарей
- vocabulariesToMany() - Сущность текущей модели "относится" к разным словарям
- termsByMany() - Сущность текущей модели "держит" много термов
- termsToMany() - Сущность текущей модели "относится" к разным термам (или `terms()` с трейта `HasTaxonomies`)

#### Scopes в модели Term
byVocabulary() - Все термы по указанному словарю (по умолчанию ключ словаря - `system_name`)

#### Scopes для работи с иерархией в термах
- hierarchyUp() - Все термы по иерархии вверх для полученного терма
- hierarchyDown() - Все термы по иерархии вниз для полученного терма

__Также в модель Term подключен ниже описанный трейт `HasTaxonomies` с доп. методамы.__

---
### Таксономия в своих моделях
Для использование в ваших моделях таксономии нужно подключить трейт `HasTaxonomies` в котором есть методи-скопы:
#### Связи
- termsByVocabulary() - Связь текущей модели с термамы по указанному словарю (по умолчанию `system_name`) - используется для создание связей с термамы нужного словаря в своих моделях
- terms() - Сущность текущей модели "относится" к разным термам (полиморфизм)

#### Scopes
- termsByVocabulary() - Термы текущей модели по указанному словарю (по умолчанию ключ `system_name`). Не путать с связью `termsByVocabulary()`!
- byTaxonomies() - Сущности по указанным термам с соответствующих указанных словарей (ключи по умолчанию term = `id`, vocabulary = `system_name`)

Можно создавать в своих моделях свои, более удобные методы для связей, на основе метода `terms()`. 
Например: модель статьи имеет термы-категории со словаря "Категории" (с ИД = 1) то связь "categories" можно настроить как в примере: 

```php
    public function categories()
    {
        $vocabularyCategory = '1';
        return $this->terms()->where('vocabulary_id', $vocabularyCategory);
    }
```

Или еще более удобно использовать связь `termsByVocabulary()` указывая системное имя нужного словаря:
```php
    public function categories()
    {
        return $this->termsByVocabulary('categories');
    }
```
И примери использования:
```php
	App\Models\Term::find(1)->vocabulary;
	
	App\Models\Term::byVocabulary('categories')->getFile();

	App\Models\Term::find(1)->hierarchyUp()->getFile();
	
	App\Models\Post::with('categories')->getFile();

	App\Models\Post::byTaxonomies([
		'categories' => [1,3,5],
		'method-oplatu' => [3]
	])->getFile();
```
