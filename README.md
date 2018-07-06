# Laravel Taxonomy
Создание и управление таксономией в Laravel Eloquent

## Установка

Запустить:
```bash
	composer require fomvasss/laravel-taxonomy
```

Для Laravel < 5.5 добавить в сервис-провайдер в app.php
```php
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

#### Работа с иерархией в термах (рекомендации)
Для работы с иерархией, нужно:
1. Установить пакет "lazychaser/laravel-nestedset";
2. переопределить модель Term, указать в конфигах `config/taxonomy.php` новый namespace модели Term;
3. подключить в модель Term трейт `NodeTrait` пакета `kalnoy/nestedset` для управления иерархией.
[Описание пакета "laravel-nestedset" и его методов](https://github.com/lazychaser/laravel-nestedset)
4. Для моделей термов станут доступные все методы вышеприведенного пакета.

---
### Таксономия в своих моделях
Для использование в ваших моделях таксономии нужно подключить трейт `HasTaxonomies` в котором есть методы и скопы:
#### Связи
- termsByVocabulary() - Связь текущей модели с термамы по указанному словарю (по умолчанию `system_name`) - используется для создание связей с термамы нужного словаря в своих моделях
- terms() - Сущность текущей модели "относится" к разным термам (полиморфизм)
- term() - Сущность имеет один терм (ключ для связи создается в таблице текущей сущности)

#### Scopes
- termsByVocabulary() - Термы текущей модели по указанному словарю (по умолчанию ключ `system_name`). Не путать с связью `termsByVocabulary()`!
- byTaxonomies() - Сущности по указанным термам с соответствующих указанных словарей (ключи по умолчанию term = `id`, vocabulary = `system_name`)

Можно создавать в своих моделях свои, более удобные методы для связей, на основе метода `terms()`. 
Например: модель статьи (Article) относится к термам-категориям (Term) со словаря (Vocabulary) "Категории статей" (vocabularies.id = 1, vocabularies.system_name=article_categories) то метод связи между статьей и ее категориями можно назвать "taxonomyArticleCategories" и записать как:

```php
    public function taxonomyArticleCategories()
    {
        $vocabularyArticleCategory = 1;
        return $this->terms()->where('vocabulary_id', $vocabularyCategory);
    }
```

Или, еще более удобно записать связь используя метод `termsByVocabulary()` и указывая системное имя нужного словаря:
```php
    public function taxonomyArticleCategories()
    {
        return $this->termsByVocabulary('article_categories');
    }
```

Например, если этот метод использовать в модели Article, то он описывает связь статьи с категориямы.
Все категории статьи можно получить:
```php
$article = Models\Article::first()->taxonomyArticleCategories;
```

Аналогично, можно использовать метод `term()` в вашей модели. Например, опишем связь статьи со статусом статьи:

```php
    public function taxonomyArticleStatus()
    {
        return $this->term('status', 'system_name')->where('type', 'article_statuses');
    }
```

- `taxonomyArticleStatus` - название метода для связи сущности со статусом
- `status` - поле в таблице текущой сущности (статьи), где хранится статус, через которое и установленная связь с термом
- `system_name` - поле в таблице термов (в нашем случае записано системное имя статуса)
- `type` - поле в таблице термов, в котором записано название словаря
- `article_statuses` - название словаря, записанное в таблице термов

Например, если этот метод использовать в сущности Article, то он описывает статус статьи.
Данные терма-статуса можно получить:
```php
$article = Models\Article::first();
$article->taxonomyArticleStatus->name;
```

И примери использования:
```php
	App\Models\Term::find(1)->vocabulary;
	
	App\Models\Term::byVocabulary('article_categories')->get();

	App\Models\Term::find(1)->descendants; // метод с пакета `lazychaser/laravel-nestedset`
	
	App\Models\Article::with('taxonomyArticleCategories')->get();
	
	App\Models\Article::first()->taxonomyArticleCategories()->attach([1, 2]);
	
	App\Models\Article::first()->taxonomyArticleCategories()->sync([4, 2]); // !!! то же самое что и:
    App\Models\Article::first()->terms()->sync([4, 2]);

	App\Models\Article::byTaxonomies([
		'article_categories' => [1,3,5],
		'payment_methods' => [3]
	])->getFile();
```
