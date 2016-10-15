<?php

namespace Muffin\Queries\Snippets;

use Muffin\Snippet;

class Update implements Snippet, NeedTableAware
{
    private
        $tables;

    public function __construct($table = null, $alias = null)
    {
        $this->tables = array();

        if(! empty($table))
        {
            $this->addTable($table, $alias);
        }
    }

    public function addTable($table, $alias = null)
    {
        if(! $table instanceof TableName)
        {
            $table = new TableName($table, $alias);
        }

        $this->tables[] = $table;

        return $this;
    }

    public function toString()
    {
        if(empty($this->tables))
        {
            return '';
        }

        $tables = array();

        foreach($this->tables as $table)
        {
            $tables[] = $table->toString();
        }

        $tablesString = implode(', ', array_filter($tables));

        return sprintf('UPDATE %s', $tablesString);
    }

    public function hasNeededTable($tableName)
    {
        foreach($this->tables as $table)
        {
            if($table->getName() === $tableName || $table->getAlias() === $tableName)
            {
                return true;
            }
        }

        return false;
    }
}
