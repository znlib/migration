<?php

namespace ZnLib\Migration\Domain\Base;

use Illuminate\Database\Schema\Builder;
use ZnLib\Db\Capsule\Manager;

abstract class BaseMigration
{

    protected $capsule;

    public function __construct(Manager $capsule)
    {
        $this->capsule = $capsule;
    }

    public function getCapsule(): Manager
    {
        return $this->capsule;
    }

    protected function runSqlQuery(Builder $schema, $sql)
    {
        $connection = $schema->getConnection();
        $rawSql = $connection->raw($sql);
        $connection->select($rawSql);
    }
}