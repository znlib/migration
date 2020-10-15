<?php

use Symfony\Component\Console\Application;
use ZnLib\Db\Capsule\Manager;

/**
 * @var Application $application
 */

$capsule = \ZnLib\Db\Factories\ManagerFactory::createManagerFromEnv();

use ZnLib\Migration\Domain\Services\MigrationService;
use ZnLib\Migration\Domain\Services\GenerateService;
use ZnLib\Migration\Domain\Repositories\File\GenerateRepository;
use ZnLib\Migration\Domain\Repositories\HistoryRepository;
use ZnLib\Migration\Domain\Repositories\SourceRepository;
use ZnLib\Migration\Commands\UpCommand;
use ZnLib\Migration\Commands\DownCommand;
use ZnLib\Migration\Commands\GenerateCommand;

$migrationService = new MigrationService(new SourceRepository($eloquentConfigFile), new HistoryRepository($capsule));
$generateService = new GenerateService(new GenerateRepository);

// создаем и объявляем команду "UP"
$upCommand = new UpCommand(UpCommand::getDefaultName(), $migrationService);
$application->add($upCommand);

// создаем и объявляем команду "Down"
$downCommand = new DownCommand(DownCommand::getDefaultName(), $migrationService);
$application->add($downCommand);

// создаем и объявляем команду "Generate"
$downCommand = new GenerateCommand(GenerateCommand::getDefaultName(), $generateService);
$application->add($downCommand);
