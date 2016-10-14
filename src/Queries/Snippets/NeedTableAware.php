<?php

namespace Muffin\Queries\Snippets;

interface NeedTableAware
{
    public function hasNeededTable($tableName);
}