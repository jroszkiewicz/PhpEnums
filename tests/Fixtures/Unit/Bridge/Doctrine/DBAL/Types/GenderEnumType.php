<?php

/*
 * This file is part of the "elao/enum" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\Enum\Tests\Fixtures\Unit\Bridge\Doctrine\DBAL\Types;

use Elao\Enum\Bridge\Doctrine\DBAL\Types\AbstractEnumType;
use Elao\Enum\Tests\Fixtures\Unit\EnumTest\Gender;

class GenderEnumType extends AbstractEnumType
{
    const NAME = 'gender_enum';

    protected function getEnumClass() : string
    {
        return Gender::class;
    }

    protected function onNullFromDatabase()
    {
        return Gender::create(Gender::UNKNOW);
    }

    protected function onNullFromPhp()
    {
        return Gender::UNKNOW;
    }

    public function getName()
    {
        return self::NAME;
    }
}
