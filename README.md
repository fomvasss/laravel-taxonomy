**Development and support of this package is discontinued! Use a better solution [fomvasss/laravel-simple-taxonomy](https://github.com/fomvasss/laravel-simple-taxonomy)**

# Laravel Taxonomy

[![License](https://img.shields.io/packagist/l/fomvasss/laravel-taxonomy.svg?style=for-the-badge)](https://packagist.org/packages/fomvasss/laravel-taxonomy)
[![Build Status](https://img.shields.io/github/stars/fomvasss/laravel-taxonomy.svg?style=for-the-badge)](https://github.com/fomvasss/laravel-taxonomy)
[![Latest Stable Version](https://img.shields.io/packagist/v/fomvasss/laravel-taxonomy.svg?style=for-the-badge)](https://packagist.org/packages/fomvasss/laravel-taxonomy)
[![Total Downloads](https://img.shields.io/packagist/dt/fomvasss/laravel-taxonomy.svg?style=for-the-badge)](https://packagist.org/packages/fomvasss/laravel-taxonomy)
[![Quality Score](https://img.shields.io/scrutinizer/g/fomvasss/laravel-taxonomy.svg?style=for-the-badge)](https://scrutinizer-ci.com/g/fomvasss/laravel-taxonomy)

Create and manage taxonomy categories (terms) in Laravel Eloquent.

## Установка

```bash
composer require fomvasss/laravel-taxonomy
```

### Publishing resources

```bash
php artisan vendor:publish --provider="Fomvasss\Taxonomy\TaxonomyServiceProvider"
```

После публикации ресурсов, вы можете изменить файл миграции, seeder и переопределить Eloquent-модели термов, словарей.

```bash
composer dump-autoload
php artisan migrate
php artisan db:seed --class=TaxonomySeeder
```

> При использовании переопределенных классов моделей терминов и словарей, нужно указать в конфигу `taxonomy.php` пути этих моделей, иначе система будет работать не так как вы планируете!

## Usage

### Usage in own models

> Для использование в ваших моделях таксономии, нужно подключить трейт `HasTaxonomies` в котором есть relation methods & scope`s:

#### Relations

- `termsByVocabulary()` - Связь текущей модели с термамы по указанному словарю (по `system_name`). Используется для создание связей с термамы нужного словаря в своих моделях
- `terms()` - Сущность текущей модели "относится" к разным термам (полиморфизм), например: товар в магазине относится к разным категориям
- `term()` - Сущность имеет один терм (ключ для связи создается в таблице текущей сущности), например: экземпляр автомобиля имеет бренд

#### Scopes

- `termsByVocabulary()` - Термы текущей модели по указанному словарю (по `system_name`). Не путать с связью `termsByVocabulary()`!
- `byTaxonomies()` - Сущности по указанным термам с соответствующих указанных словарей (ключи по умолчанию: `vocabulary.system_name`, `term.id`)

Можно создавать в своих моделях, свои, более удобные, методы для связей, на основе метода-связи `terms()`. 

Например: модель статьи `Article` относится к разным термам-категориям `Term` со словаря (`Vocabulary`) "Категории статей" (`vocabularies.id` = 1, `vocabularies.system_name` = "article_categories") то метод связи между статьей и ее категориями можно назвать `txArticleCategories` и записать как:

> Удобно строить связь используя метод `termsByVocabulary()` и указывая системное имя нужного словаря:

```php
public function txArticleCategories()
{
    return $this->termsByVocabulary('article_categories');
}
```

> В ваших методах, для связей с таксономией (термины, словари), рекомендуется использовать как стандарт префикс "tx" (`txArticleCategories`, `txArticleStatus`, `txBrands` `txRegion`,...) - это только рекомендация:)

Например, если метод `txArticleCategories()` использовать в модели `Article`, то он описывает связь статьи с категориямы.

Все категории статьи можно получить:
```php
$article = Models\Article::first()->txArticleCategories;
```

Аналогично, можно использовать метод `term()` в вашей модели. Например, опишем связь статьи с термом словаря статусов модерации статьи,
при этом укажем поля для связей модели терма-статуса с самой моделью статьи:

```php
public function txArticleStatus()
{
    return $this->term('status', 'system_name')
        ->where('vocabulary', 'article_statuses');
}
```
где:
- `txArticleStatus` - название метода для связи сущности-статьи с термом-статусом (можно любое, по правилам Laravel)
- `status` - поле в таблице текущой сущности (статьи), где хранится статус, через которое и установленная связь с термом (`articles.status`)
- `system_name` - поле в таблице термов (в нашем случае записано системное имя статуса) (`terms.system_name`)
- `vocabulary` - поле в таблице термов, в котором записано систимное название словаря (`terms.vocabulary`)
- `article_statuses` - название словаря, записанное в таблице термов (`terms.vocabulary`)

Например, если метод `txArticleStatus()` использовать в модели `Article`, то он описывает статус статьи.

Данные терма-статуса, например имя статуса, можно получить:
```php
$article = \App\Models\Article::first();
$article->txArticleStatus->name;
```

В переопределенной модели `Term` вы можете описать метод, для получения всех статтей по терму-категории:
```php
public function articles()
{
    return $this->morphedByMany(Article::class, 'termable');
}
```
И использовать его:
```php
    Term::byVocabulary('article_categories')->first(13)->articles
```


### Методы в модели Vocabulary

#### Связи
- `terms()` - Словарь имет много термов

#### Полиморфные связи
- `vocabulariesByMany()` - Сущность текущей модели "держит" много словарей
- `vocabulariesToMany()` - Сущность текущей модели "относится" к разным словарям
- `termsByMany()` - Сущность текущей модели "держит" много термов
- `termsToMany()` - Сущность текущей модели "относится" к разным термам

---
### Методи в модели Term

#### Связи
- `txVocabulary()` - Терм "пренадлежит" одному словарю

#### Полиморфные связи
- `vocabulariesByMany()` - Сущность текущей модели "держит" много словарей
- `vocabulariesToMany()` - Сущность текущей модели "относится" к разным словарям
- `termsByMany()` - Сущность текущей модели "держит" много термов
- `termsToMany()` - Сущность текущей модели "относится" к разным термам (или `terms()` с трейта `HasTaxonomies`)

#### Scopes в модели Term
- `byVocabulary()` - Все термы по указанному словарю

#### Работа с иерархией в терминах таксономии (рекомендации)
- Работа с иерархией в этом пакете построена на ["laravel-nestedset"](https://github.com/lazychaser/laravel-nestedset)
- Для моделей термов доступны все методы пакета `laravel-nestedset`.

---

## Еще примеры использования:
```php
\App\Models\Taxonomy\Term::find(1)->vocabulary; // get system name vocabulary

\App\Models\Taxonomy\Term::find(1)->txVocabulary; // get related model vocabulary

\App\Models\Taxonomy\Term::byVocabulary('article_categories')->get(); // get terms by system name vocabulary

\App\Models\Taxonomy\Term::byVocabulary('article_categories')->get()->toTree(); // `toTree` - method from package `lazychaser/laravel-nestedset`

\App\Models\Taxonomy\Term::find(1)->descendants; // `descendants` - method from package `lazychaser/laravel-nestedset`

\App\Models\Article::with('txArticleCategories')->get(); // get articles with article categories

\App\Models\Article::first()->txArticleCategories()->attach([1, 2]);

\App\Models\Article::first()->txArticleCategories()->sync([4, 2]); // this detach all terms in article and sync 4 ,2!!! Same as:

\App\Models\Article::first()->terms()->sync([4, 2]);

\App\Models\Article::first()->terms()->detach([4]);

\App\Models\Article::first()->txArticleCategories()->syncWithoutDetaching([4, 2]); // sync terms without detaching

\App\Models\Article::byTaxonomies([
    'article_categories' => [1,3,5],
    'cities' => [3]
])->get(); // use for example for filters
```

## Links
* [https://github.com/lazychaser/laravel-nestedset](https://github.com/lazychaser/laravel-nestedset)
* [https://en.wikipedia.org/wiki/Taxonomy_(general)](https://en.wikipedia.org/wiki/Taxonomy_(general))
* [https://en.wikipedia.org/wiki/Nesting_(computing)](https://en.wikipedia.org/wiki/Nesting_(computing))
