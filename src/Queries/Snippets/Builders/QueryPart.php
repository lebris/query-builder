<?php

namespace Muffin\Queries\Snippets\Builders;

trait QueryPart
{
    public function add(\Muffin\QueryPart $queryPart)
    {
        $queryPart->build($this);

        return $this;
    }
}
