<?php

namespace Fomvasss\Taxonomy;

use Illuminate\Support\ServiceProvider;

class TaxonomyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishConfig();

        $this->publishMigrations();

        $this->publishSeeder();

        $this->overrideModels();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/taxonomy.php', 'taxonomy');
    }

    protected function publishConfig()
    {
        $this->publishes([__DIR__ . '/../config/taxonomy.php' => config_path('taxonomy.php')
        ], 'taxonomy-config');
    }

    protected function publishSeeder()
    {
        $seedPath = __DIR__ . '/../database/seeds/TaxonomyTableSeeder.php.stub.php';
        $this->publishes([$seedPath => database_path('seeds/TaxonomyTableSeeder.php')
            ], 'taxonomy-seeder');
    }

    protected function publishMigrations()
    {
        if (! class_exists('CreateTaxonomiesTable')) {
            $timestamp = date('Y_m_d_His', time());

            $migrationPath = __DIR__.'/../database/migrations/create_taxonomies_table.php';
            $this->publishes([$migrationPath => database_path('/migrations/' . $timestamp . '_create_taxonomies_table.php'),
                ], 'taxonomy-migrations');
        }
    }

    protected function overrideModels()
    {
        $modelPathStub = __DIR__.'/stubs/models/';
        $modelPath = $this->checkMakeDir(app_path('Models/Taxonomies')) . '/';

        if (! class_exists('App\Models\Term\Taxonomies') || ! class_exists('App\Models\Vocabulary\Taxonomies')) {
            $this->publishes([
                $modelPathStub . 'Term.php.stub' => $modelPath . 'Term.php',
                $modelPathStub . 'Vocabulary.php.stub' => $modelPath . 'Vocabulary.php',
            ], 'taxonomy-models');
        }
    }

    protected function checkMakeDir(string $path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        return $path;
    }
}
