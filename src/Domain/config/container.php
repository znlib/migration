<?php

use ZnLib\Migration\Domain\Repositories\SourceRepository;

return [
    'definitions' => [],
    'singletons' => [
        'ZnLib\Migration\Domain\Interfaces\Services\GenerateServiceInterface' => 'ZnLib\Migration\Domain\Services\GenerateService',
        'ZnLib\Migration\Domain\Interfaces\Repositories\GenerateRepositoryInterface' => 'ZnLib\Migration\Domain\Repositories\File\GenerateRepository',
        SourceRepository::class => function () {
            return new SourceRepository($_ENV['ELOQUENT_CONFIG_FILE']);
        },
    ],
];
