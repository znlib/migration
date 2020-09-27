<?php

namespace ZnLib\Migration\Domain\Repositories\File;

use ZnLib\Migration\Domain\Entities\GenerateEntity;
use ZnLib\Migration\Domain\Interfaces\Repositories\GenerateRepositoryInterface;

class GenerateRepository implements GenerateRepositoryInterface
{

    protected $tableName = 'migration_generate';

    public function getEntityClass(): string
    {
        return GenerateEntity::class;
    }
}
