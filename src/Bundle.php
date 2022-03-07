<?php

namespace ZnLib\Migration;

use ZnCore\Base\Libs\App\Base\BaseBundle;

class Bundle extends BaseBundle
{

    public function deps(): array
    {
        return [
            new \ZnDatabase\Migration\Bundle(['all']),
        ];
    }
}
