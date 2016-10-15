<?php

namespace Muffin;

interface QueryPart
{
    public function build(QueryPartAware $query);
}
