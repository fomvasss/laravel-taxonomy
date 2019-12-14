<?php

use Illuminate\Database\Seeder;

class TaxonomyTableSeeder extends Seeder
{
    protected $termModel;

    protected $vocabularyModel;

    /**
     * TaxonomyTableSeeder constructor.
     */
    public function __construct()
    {
        $this->termModel = config('taxonomy.models.term');
        $this->vocabularyModel = config('taxonomy.models.vocabulary');
    }
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedVocabularies($this->getData());
    }

    /**
     * Specify the taxonomy structure you need.
     *
     * @return array
     */
    public function getData()
    {
        // This data is example:
        return [
            [
                'system_name' => 'product_categories',
                'name' => 'Категории товаров',
                'description' => 'Категории товаров магазина',
                'terms' => [
                    [
                        'name' => 'Автоэлектроника',
                        'description' => 'Электроника для вашего авто',
                        'terms' => [
                            ['name' => 'Автосигнализации', 'description' => 'Автосигнализации и соп. товары'],
                            ['name' => 'GPS навигаторы', 'description' => 'GPS навигаторы и треккеры'],
                            ['name' => 'Видеорегистраторы', 'description' => 'Видеорегистраторы и камеры для авто'],
                        ],
                    ],
                    ['name' => 'Колеса и диски', 'description' => 'Шины, диски, колпаки'],
                    [
                        'name' => 'Автохимия',
                        'description' => 'Автохимия и автокосметика',
                        'terms' => [
                            ['name' => 'Омиватели', 'description' => 'Омиватели летние и зимнии для стекла'],
                            [
                                'name' => 'Чистка кузова',
                                'description' => 'Инструменты и химия для чистки кузова',
                                'terms' => [
                                    ['name' => 'Шампуни', 'description' => 'Шампуни для мойки'],
                                    ['name' => 'Воск', 'description' => 'Воск и полироли'],
                                ],
                            ],
                        ],
                    ],
                    ['name' => 'Тюнинг', 'description' => 'Спойлеры и наклейки для кузова авто'],
                ],
            ],
            [
                'system_name' => 'product_brands',
                'name' => 'Бренды товаров',
                'description' => 'Бренды товаров магазина',
                'terms' => [
                    ['name' => 'Tesla', 'description' => '',],
                    ['name' => 'BMW', 'description' => '',],
                    ['name' => 'Mercedes', 'description' => '',],
                    ['name' => 'Peugeot', 'description' => '',],
                    ['name' => 'Ford', 'description' => '',],
                ],
            ],
            [
                'system_name' => 'order_statuses',
                'name' => 'Статусы заказов',
                'description' => 'Статусы заказов',
                'terms' => [
                    ['name' => 'Новый заказ', 'description' => '', 'system_name' => 'order_new'],
                    ['name' => 'Отправлен клиенту', 'description' => '', 'system_name' => 'order_shipping',],
                    ['name' => 'Успешно получен', 'description' => '', 'system_name' => 'order_accept',],
                    ['name' => 'Отклонен', 'description' => '', 'system_name' => 'order_rejected',],
                    ['name' => 'Отказ', 'description' => '', 'system_name' => 'order_refund',],
                ],
            ],
            [
                'system_name' => 'regions',
                'name' => 'Регионы',
                'description' => 'Регионы страны',
                'terms' => [
                    ['name' => 'Кировоградская', 'description' => 'Кировоградская область'],
                    ['name' => 'Волынская', 'description' => 'Волынская область'],
                    ['name' => 'Киевская', 'description' => 'Киевская область'],
                    ['name' => 'Донецкая', 'description' => 'Донецкая область'],
                ],
            ],
            [
                'system_name' => 'quantities',
                'name' => 'Единицы измерения количества',
                'description' => 'Словарь едениц измерения количества товаров/услуг',
                'terms' => [
                    ['name' => 'см.', 'description' => 'сантиметров',],
                    ['name' => 'т.', 'description' => 'тонн',],
                    ['name' => 'шт.', 'description' => 'штук',],
                ],
            ],
            [
                'system_name' => 'payment_methods',
                'name' => 'Способы оплаты',
                'description' => 'Способы оплаты товаров/услуг',
                'terms' => [
                    ['name' => 'Предоплата', 'description' => 'оплата проводится наперед', 'system_name' => 'pay_prepay',],
                    ['name' => 'Оплата при получении', 'description' => 'оплата проводится при получении товара', 'system_name' => 'pay_upon_receipt',],
                ],
            ],
            [
                'system_name' => 'payment_statuses',
                'name' => 'Статусы оплаты',
                'description' => 'Статусы оплаты товаров/услуг',
                'terms' => [
                    ['name' => 'Не оплачено', 'description' => 'Новый платеж, ожидает оплаты', 'system_name' => 'payment_new',],
                    ['name' => 'Оплата успешна', 'description' => 'Оплата проведена успешно', 'system_name' => 'payment_success',],
                    ['name' => 'Ошибка оплаты', 'description' => 'Оплата не проведена', 'system_name' => 'payment_fail',],
                ],
            ],
            [
                'system_name' => 'post_statuses',
                'name' => 'Статусы объявлений',
                'description' => 'Статусы модерации объявлений',
                'terms' => [
                    ['name' => 'На модерации', 'description' => 'Обявление находится на модерации', 'system_name' => 'post_moderation',],
                    ['name' => 'Опубликовано', 'description' => 'Обявление успешно опубликовано', 'system_name' => 'post_published',],
                    ['name' => 'Отклонено', 'description' => 'Обявление отклонено для публикации', 'system_name' => 'post_rejected',],
                    ['name' => 'В архиве', 'description' => 'Обявление находится в архиве', 'system_name' => 'post_archived',],
                ],
            ],
        ];
    }

    /**
     * @param array $vocabularies
     */
    protected function seedVocabularies(array $vocabularies)
    {
        foreach ($vocabularies as $item) {
            $vocabulary = $this->vocabularyModel::updateOrCreate([
                'system_name' => $item['system_name'], // TODO: For this test name is unique !!!
            ], [
                'name' => $item['name'],
                'description' => $item['description'] ?? null,
            ]);
            $this->command->info("Vocabulary saved: $vocabulary->name ($vocabulary->id)");

            if (! empty($item['terms'])) {
                $this->seedTerms($item['terms'], $vocabulary);
            }
        }
    }

    /**
     * @param array $terms
     * @param int $vocabulary
     * @param null $parentId
     */
    protected function seedTerms(array $terms, $vocabulary, $parent_id = null)
    {
        foreach ($terms as $item) {
            $term = $this->termModel::updateOrCreate([
                'name' => $item['name'], // TODO: For this test name is unique !!!
            ], [
                'system_name' => isset($item['system_name']) ? str_slug($item['system_name'], '_') : null,
                'description' => $item['description'] ?? null,
                'vocabulary' => $vocabulary->system_name,
                'parent_id' => $parent_id,
            ]);

            $this->command->info(" - Term saved: $term->name ($term->id)");

            if (! empty($item['terms'])) {
                $this->seedTerms($item['terms'], $vocabulary, $term->id);
            }
        }
    }
}
