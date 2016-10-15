<?php

namespace Muffin\Tests\Fixtures\QueryParts;

use Muffin\QueryPart;
use Muffin\QueryPartAware;
use Muffin\Conditions;
use Muffin\Types;
use Muffin\Conditions\Sets\AndSet;

class IsCute implements QueryPart
{
    public function build(QueryPartAware $query)
    {
        $conditionSet = new AndSet();
        $conditionSet
            ->add(new Conditions\Equal(new Types\String('color'), 'white'))
            ->add(new Conditions\Lower(new Types\Integer('age'), 1));

        $query->where($conditionSet);

        return $query;
    }
}
