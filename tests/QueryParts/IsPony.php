<?php

namespace Muffin\Tests\QueryParts;

use Muffin\QueryPart;
use Muffin\QueryPartAware;
use Muffin\Conditions;
use Muffin\Types;

class IsPony implements QueryPart
{
    public function build(QueryPartAware $query)
    {
        $query
            ->where(new Conditions\Equal(new Types\String('type'), 'pony'));

        return $query;
    }
}
