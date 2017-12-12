<?php

namespace Fomvasss\Taxonomy\Commands;

use Fomvasss\Taxonomy\TaxonomyServiceProvider;
use Illuminate\Console\Command;

/**
 * Class TaxonomyMigrate
 *
 * @package \Fomvasss\Taxonomy
 */
class TaxonomyPublish extends Command
{
    protected $signature = 'taxonomy:publish
            {--config} {--migrations} {--seeder} {--models} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the taxonomy files';
    /**
     * Create a new command instance.
     */

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $options = $this->options();

        $params = $this->getAllowedParams($options);

        $this->runPublish($params);
    }

    /**
     * @param array $options
     * @return array
     */
    protected function getAllowedParams(array $options): array
    {
        $allowedParams = ['migration', 'config', 'seeder', 'models'];
        if (empty($options['all'])) {
            $params = array_keys($options, $allowedParams);
        } else {
            $params = $allowedParams;
        };

        return $params;
    }

    /**
     * @param array $params
     */
    protected function runPublish(array $params)
    {
        if (count($params)) {
            foreach ($params as $value) {
                $this->call('vendor:publish', [
                    '--provider' => TaxonomyServiceProvider::class,
                    '--tag' => 'taxonomy-' . $value,
                ]);
            }
            exec('composer dump-autoload');
            $this->info('Composer dump-autoload complete.');
        } else {
            $this->warn('Parameters not found. See: php artisan taxonomy:publish --help');
        }
    }
}
