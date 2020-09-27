<?php

namespace ZnLib\Migration\Domain\Repositories;

use Illuminate\Database\Schema\Blueprint;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnLib\Db\Base\BaseEloquentRepository;
use ZnLib\Migration\Domain\Base\BaseCreateTableMigration;
use ZnLib\Migration\Domain\Entities\MigrationEntity;
use ZnCore\Base\Helpers\ClassHelper;
use ZnLib\Migration\Domain\Interfaces\MigrationInterface;

//use ZnLib\Db\Helpers\TableAliasHelper;

class HistoryRepository extends BaseEloquentRepository
{

    const MIGRATION_TABLE_NAME = 'eq_migration';

    protected $tableName = self::MIGRATION_TABLE_NAME;

    public function getEntityClass(): string
    {
        return MigrationEntity::class;
    }

    public static function filterVersion(array $sourceCollection, array $historyCollection)
    {
        /**
         * @var MigrationEntity[] $historyCollection
         * @var MigrationEntity[] $sourceCollection
         */

        $sourceVersionArray = ArrayHelper::getColumn($sourceCollection, 'version');
        $historyVersionArray = ArrayHelper::getColumn($historyCollection, 'version');

        $diff = array_diff($sourceVersionArray, $historyVersionArray);

        foreach ($sourceCollection as $key => $migrationEntity) {
            if ( ! in_array($migrationEntity->version, $diff)) {
                unset($sourceCollection[$key]);
            }
        }
        return $sourceCollection;
    }

    private function insert($version, $connectionName = 'default')
    {
        $tableAlias = $this->getCapsule()->getAlias();
        $targetTableName = $tableAlias->encode($connectionName, self::MIGRATION_TABLE_NAME);
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert([
            'version' => $version,
            'executed_at' => new \DateTime(),
        ]);
    }

    private function delete($version, $connectionName = 'default')
    {
        $tableAlias = $this->getCapsule()->getAlias();
        $targetTableName = $tableAlias->encode($connectionName, self::MIGRATION_TABLE_NAME);
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->where('version', $version);
        $queryBuilder->delete();
    }

    public function upMigration(string $class)
    {
        $migration = $this->createMigrationClass($class);
        $schema = $this->getSchema();
        $connection = $schema->getConnection();
        // todo: begin transaction
        $connection->beginTransaction();
        $migration->up($schema);
        $version = ClassHelper::getClassOfClassName($class);
        $this->insert($version);
        $connection->commit();
        // todo: end transaction
    }

    public function downMigration(string $class)
    {
        $migration = $this->createMigrationClass($class);
        $schema = $this->getSchema();
        $connection = $schema->getConnection();
        // todo: begin transaction
        $connection->beginTransaction();
        $migration->down($schema);
        $version = ClassHelper::getClassOfClassName($class);
        self::delete($version);
        $connection->commit();
        // todo: end transaction
    }

    private function createMigrationClass(string $class): MigrationInterface {
        $migration = new $class($this->getCapsule());
        ClassHelper::isInstanceOf($migration, MigrationInterface::class);
        return $migration;
    }

    public function all($connectionName = 'default')
    {
        $this->forgeMigrationTable($connectionName);
        $queryBuilder = $this->getQueryBuilder();
        $array = $queryBuilder->get()->toArray();
        $collection = [];
        foreach ($array as $item) {
            $entityClass = $this->getEntityClass();
            $entity = new $entityClass;
            $entity->version = $item->version;
            //$entity->className = $className;
            $collection[] = $entity;
        }
        return $collection;
    }

    private function forgeMigrationTable($connectionName = 'default')
    {
        $schema = $this->getSchema($connectionName);
        $tableAlias = $this->getCapsule()->getAlias();
        $targetTableName = $tableAlias->encode($connectionName, self::MIGRATION_TABLE_NAME);
        $hasTable = $schema->hasTable($targetTableName);
        if ($hasTable) {
            return;
        }
        $this->createMigrationTable($connectionName);
    }

    private function createMigrationTable($connectionName = 'default')
    {
        $tableSchema = function (Blueprint $table) {
            $table->string('version')->primary();
            $table->timestamp('executed_at');
        };
        $schema = $this->getSchema($connectionName);
        $tableAlias = $this->getCapsule()->getAlias();
        $targetTableName = $tableAlias->encode($connectionName, self::MIGRATION_TABLE_NAME);
        $schema->create($targetTableName, $tableSchema);
    }

}