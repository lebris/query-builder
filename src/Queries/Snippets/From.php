<?php

namespace Muffin\Queries\Snippets;

use Muffin\Snippet;

class From implements Snippet, NeedTableAware
{
    private
        $tableName;

    public function __construct($table, $alias = null)
    {
        if(! $table instanceof TableName)
        {
            $table = new TableName($table, $alias);
        }

        $this->tableName = $table;
    }

    public function toString()
    {
        return sprintf('FROM %s', $this->tableName->toString());
    }

    public function hasNeededTable($tableName)
    {
        if($this->tableName->getName() === $tableName || $this->tableName->getAlias() === $tableName)
        {
            return true;
        }

        return false;
    }
}
