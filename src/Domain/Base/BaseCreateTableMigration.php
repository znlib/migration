<?php

namespace ZnLib\Migration\Domain\Base;

use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\DB;
use ZnLib\Db\Enums\DbDriverEnum;
use ZnLib\Db\Factories\ManagerFactory;
use ZnLib\Db\Helpers\DbHelper;
use ZnLib\Db\Capsule\Manager;
use ZnLib\Db\Helpers\SqlHelper;
use ZnLib\Db\Traits\TableNameTrait;
use ZnLib\Migration\Domain\Interfaces\MigrationInterface;

abstract class BaseCreateTableMigration extends BaseMigration implements MigrationInterface
{

    use TableNameTrait;

    protected $tableComment = '';

    abstract public function tableSchema();

    public function getConnection(): Connection
    {
        return $this->capsule->getConnection($this->connectionName());
    }

    public function up(Builder $schema)
    {
        $isHasSchema = SqlHelper::isHasSchemaInTableName($this->tableNameAlias());
        if ($isHasSchema) {
            $schemaName = SqlHelper::extractSchemaFormTableName($this->tableNameAlias());
            $this->getConnection()->statement('CREATE SCHEMA IF NOT EXISTS "' . $schemaName . '";');
        }
        $schema->create($this->tableNameAlias(), $this->tableSchema());
        if ($this->tableComment) {
            $this->addTableComment($schema);
        }
    }

    public function down(Builder $schema)
    {
        $schema->dropIfExists($this->tableNameAlias());
    }

    private function addTableComment(Builder $schema)
    {
        $connection = $this->getConnection();
        $driver = $connection->getConfig('driver');
        $table = $this->tableNameAlias();
        $quotedTableName = SqlHelper::generateRawTableName($table);
        $tableComment = $this->tableComment;
        $sql = '';
        if ($driver == DbDriverEnum::MYSQL) {
            $sql = "ALTER TABLE {$table} COMMENT '{$tableComment}';";
        }
        if ($driver == DbDriverEnum::PGSQL) {
            $sql = "COMMENT ON TABLE {$quotedTableName} IS '{$tableComment}';";
        }
        if ($sql) {
            $this->runSqlQuery($schema, $sql);
        }
    }

}