<?php

namespace ZnLib\Migration\Domain\Enums;

class ForeignActionEnum
{

    const NO_ACTION = 'NO ACTION';
    const RESTRICT = 'RESTRICT';
    const CASCADE = 'CASCADE';
    const SET_NULL = 'SET NULL';
    const SET_DEFAULT = 'SET DEFAULT';

}