<?php

namespace ZnLib\Migration\Domain\Repositories;

use ZnCore\Base\Exceptions\InvalidConfigException;
use ZnCore\Base\Helpers\LoadHelper;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;
use ZnCore\Base\Libs\DotEnv\DotEnv;
use ZnDatabase\Fixture\Domain\Traits\ConfigTrait;
use ZnLib\Migration\Domain\Entities\MigrationEntity;

class SourceRepository
{

    //use ConfigTrait;

    public function __construct($mainConfigFile = null)
    {
        $config = LoadHelper::loadConfig($mainConfigFile);
        //dd($_ENV['ELOQUENT_MIGRATIONS']);
        //$config = $this->loadConfig($mainConfigFile);
        $this->config = $config['migrate'] ?? [];
        $this->config['directory'] = isset($this->config['directory']) ? $this->config['directory'] : DotEnv::get('ELOQUENT_MIGRATIONS', []);
        /*if(empty($this->config)) {
            throw new InvalidConfigException('Empty migrtion configuration!');
        }*/
    }

    public function getAll()
    {
        $directories = $this->config['directory'];
        if(empty($directories)) {
            return [];
            throw new InvalidConfigException('Empty directories configuration for migrtion!');
        }
        $classes = [];
        foreach ($directories as $dir) {
            $newClasses = self::scanDir(FileHelper::prepareRootPath($dir));
            $classes = ArrayHelper::merge($classes, $newClasses);
        }
        return $classes;
    }

    private static function scanDir($dir)
    {
        $files = FileHelper::scanDir($dir);
        $classes = [];
        foreach ($files as $file) {
            $classNameClean = FileHelper::fileRemoveExt($file);
            $entity = new MigrationEntity;
            $entity->className = 'Migrations\\' . $classNameClean;
            $entity->fileName = $dir . DIRECTORY_SEPARATOR . $classNameClean . '.php';
            $entity->version = $classNameClean;
            include_once($entity->fileName);
            $classes[] = $entity;
        }
        return $classes;
    }

}