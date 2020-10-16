<?php

namespace ZnLib\Migration\Domain\Base;

use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Builder;
use ZnLib\Db\Traits\TableNameTrait;

abstract class BaseMigration
{

    use TableNameTrait;

    public function getConnection(): Connection
    {
        return $this->capsule->getConnection($this->connectionName());
    }

    protected function runSqlQuery(Builder $schema, $sql)
    {
        $connection = $schema->getConnection();
        $rawSql = $connection->raw($sql);
        $connection->select($rawSql);
    }

}