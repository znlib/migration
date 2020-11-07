<?php

namespace ZnLib\Migration\Domain\Base;

use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\DB;
use ZnLib\Db\Enums\DbDriverEnum;
use ZnLib\Db\Factories\ManagerFactory;
use ZnLib\Db\Helpers\DbHelper;
use ZnLib\Db\Capsule\Manager;
use ZnLib\Db\Helpers\SqlHelper;
use ZnLib\Migration\Domain\Interfaces\MigrationInterface;

abstract class BaseCreateTableMigration extends BaseMigration implements MigrationInterface
{

    protected $tableComment = '';
    protected $capsule;

    abstract public function tableSchema();

    public function __construct(Manager $capsule)
    {
        $this->capsule = ManagerFactory::createManagerFromEnv();
//        $this->capsule = $capsule;
    }

    public function getCapsule(): Manager
    {
        return $this->capsule;
    }

    public function up(Builder $schema)
    {
        $isHasSchema = SqlHelper::isHasSchemaInTableName($this->tableNameAlias());
        if ($isHasSchema) {
            $schemaName = SqlHelper::extractSchemaFormTableName($this->tableNameAlias());
            $this->getConnection()->select('CREATE SCHEMA IF NOT EXISTS "' . $schemaName . '";');
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
        $table = SqlHelper::generateRawTableName($table);
        $tableComment = $this->tableComment;
        $sql = '';
        if ($driver == DbDriverEnum::MYSQL) {
            $sql = "ALTER TABLE {$table} COMMENT = '{$tableComment}';";
        }
        if ($driver == DbDriverEnum::PGSQL) {
            $sql = "COMMENT ON TABLE {$table} IS '{$tableComment}';";
        }
        if ($sql) {
            $this->runSqlQuery($schema, $sql);
        }
    }

}