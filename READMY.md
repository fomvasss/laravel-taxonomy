# Laravel taxonomy package


## Установка

В `composer.json` на первый уровень добавать секцию:
```json
{
"repositories": [
	{
		"type": "vcs",
		"url": "git@bitbucket.org:fomvasss/laravel-taxonomy.git"
	}
]
}
```

Запустить:
```bash
	composer require fomvasss/laravel-taxonomy
```

Для Laravel > 5.5 добавить в сервис-провайдер в app.php
```php
<?php
	Fomvasss\Taxonomy\TaxonomyServiceProvider::class,
```

## Команды работы с пакетом
```bash
	php artisan taxonomy:publish --migration --config --seeder --models --all
```
Опубликовать в соот. папки:
`--migration` - миграцию
`--config` - конфиг (с тестовымы данными для сидера с таксономией)
`--seeder` - сидер
`--models` - модели `Vocabulary` и `Term` в `app/Models`
`--all` - все выше названное

```bash
	php artisan migrate
	php artisan db:seed --class=TaxonomyTableSeeder
```

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
- byTaxonomies() - Сущности по указанным термам с соответствующих указанных словарей

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
	
	App\Models\Term::byVocabulary('categories')->get();

	App\Models\Term::find(1)->hierarchyUp()->get();
	
	App\Models\Post::with('categories')->get();

	App\Models\Post::byTaxonomies([
		'categories' => [1,3,5],
		'method-oplatu' => [3]
	])->get();
```