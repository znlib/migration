<?php

namespace ZnLib\Migration\Domain\Base;

use Illuminate\Database\Schema\Builder;
use ZnDatabase\Eloquent\Domain\Capsule\Manager;
use ZnDatabase\Eloquent\Domain\Traits\EloquentTrait;
use ZnDatabase\Base\Domain\Repositories\Eloquent\SchemaRepository;

abstract class BaseMigration
{

    use EloquentTrait;

//    protected $capsule;

//    protected $schemaRepository;

    public function __construct(Manager $capsule, SchemaRepository $schemaRepository)
    {
        $this->setCapsule($capsule);
//        $this->schemaRepository = $schemaRepository;
    }

    /*public function getCapsule(): Manager
    {
        return $this->schemaRepository->getCapsule();
//        return $this->capsule;
    }*/

    protected function runSqlQuery(Builder $schema, $sql)
    {
        $connection = $schema->getConnection();
        $rawSql = $connection->raw($sql);
        $connection->select($rawSql);
    }
}