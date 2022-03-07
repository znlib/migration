<?php

namespace ZnLib\Migration\Domain\Base;

use ZnCore\Base\Helpers\DeprecateHelper;

DeprecateHelper::softThrow();

/**
 * @deprecated 
 */
abstract class BaseColumnMigration extends \ZnDatabase\Migration\Domain\Base\BaseColumnMigration
{
    
}
