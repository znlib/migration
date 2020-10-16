<?php

use Symfony\Component\Console\Application;
use ZnLib\Db\Capsule\Manager;
use ZnLib\Migration\Domain\Services\MigrationService;
use ZnLib\Migration\Domain\Services\GenerateService;
use ZnLib\Migration\Domain\Repositories\File\GenerateRepository;
use ZnLib\Migration\Domain\Repositories\HistoryRepository;
use ZnLib\Migration\Domain\Repositories\SourceRepository;
use ZnLib\Migration\Commands\UpCommand;
use ZnLib\Migration\Commands\DownCommand;
use ZnLib\Migration\Commands\GenerateCommand;
use Illuminate\Container\Container;
use ZnLib\Console\Symfony4\Helpers\CommandHelper;

/**
 * @var Application $application
 * @var Container $container
 */

//$capsule = $container->get(Manager::class);

$container->bind('ZnLib\Migration\Domain\Interfaces\Services\GenerateServiceInterface', 'ZnLib\Migration\Domain\Services\GenerateService');
$container->bind('ZnLib\Migration\Domain\Interfaces\Repositories\GenerateRepositoryInterface', 'ZnLib\Migration\Domain\Repositories\File\GenerateRepository');

$container->bind(SourceRepository::class, function () {
    return new SourceRepository($_ENV['ELOQUENT_CONFIG_FILE']);
});

CommandHelper::registerFromNamespaceList([
    'ZnLib\Migration\Commands'
], $container);
