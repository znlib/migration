<?php

namespace ZnLib\Migration\Domain\Base;

use ZnCore\Base\Helpers\DeprecateHelper;

DeprecateHelper::softThrow();

/**
 * @deprecated
 */
abstract class BaseMigration extends \ZnDatabase\Migration\Domain\Base\BaseMigration
{

}