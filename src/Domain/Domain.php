<?php

namespace ZnLib\Migration\Domain;

use ZnCore\Domain\Interfaces\DomainInterface;

class Domain implements DomainInterface
{

    public function getName()
    {
        return 'migration';
    }


}

