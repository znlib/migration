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
use Psr\Container\ContainerInterface;
use ZnCore\Domain\Libs\EntityManager;
use ZnCore\Domain\Interfaces\Libs\EntityManagerInterface;

/**
 * @var Application $application
 * @var Container $container
 */

//$capsule = $container->get(Manager::class);

$em = new EntityManager($container);
$container->bind(EntityManagerInterface::class, function (ContainerInterface $container) use ($em) {
    return $em;
});

$container->bind('ZnLib\Migration\Domain\Interfaces\Services\GenerateServiceInterface', 'ZnLib\Migration\Domain\Services\GenerateService');
$container->bind('ZnLib\Migration\Domain\Interfaces\Repositories\GenerateRepositoryInterface', 'ZnLib\Migration\Domain\Repositories\File\GenerateRepository');

$container->bind(ContainerInterface::class, function (ContainerInterface $container) {
    return $container;
});
$container->bind(SourceRepository::class, function () {
    return new SourceRepository($_ENV['ELOQUENT_CONFIG_FILE']);
});

CommandHelper::registerFromNamespaceList([
    'ZnLib\Migration\Commands'
], $container);
