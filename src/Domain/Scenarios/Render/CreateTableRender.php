<?php

namespace ZnLib\Migration\Domain\Scenarios\Render;

use Zend\Code\Generator\FileGenerator;
use ZnCore\Base\Legacy\Code\entities\ClassEntity;
use ZnCore\Base\Legacy\Code\entities\ClassUseEntity;
use ZnCore\Base\Legacy\Code\entities\ClassVariableEntity;
use ZnCore\Base\Legacy\Code\entities\InterfaceEntity;
use ZnCore\Base\Legacy\Code\enums\AccessEnum;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;
use ZnTool\Dev\Generator\Domain\Helpers\TemplateCodeHelper;
use ZnTool\Package\Domain\Helpers\PackageHelper;

class CreateTableRender extends BaseRender
{

    public function classDir()
    {
        return 'Migrations';
    }

    public function run()
    {
        $this->createClass();
    }

    protected function getClassName(): string
    {
        $timeStr = date('Y_m_d_His');
        $className = "m_{$timeStr}_create_{$this->dto->tableName}_table";
        return $className;
    }

    protected function createClass()
    {
        $fileGenerator = new FileGenerator;

        $fileGenerator->setNamespace('Migrations');
        $fileGenerator->setUse('Illuminate\Database\Schema\Blueprint');
        $fileGenerator->setUse('ZnLib\Migration\Domain\Base\BaseCreateTableMigration');

        $code = TemplateCodeHelper::generateMigrationClassCode($this->getClassName(), $this->dto->attributes, $this->dto->tableName);

        $fileGenerator->setBody($code);
        $fileName = $this->getFileName();

        FileHelper::save($fileName, $fileGenerator->generate());
    }

    private function getFileName()
    {
        $className = $this->getClassName();
        $dir = PackageHelper::pathByNamespace($this->dto->domainNamespace . '/' . $this->classDir());
        $fileName = $dir . '/' . $className . '.php';
        return $fileName;
    }
}
