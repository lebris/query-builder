<?php

namespace Muffin\Queries\Snippets\Builders;

use Muffin\Queries\Snippets\NeedTableAware;

trait QueryPart
{
    private
        $neededTableNames = [];

    public function add(\Muffin\QueryPart $queryPart)
    {
        $queryPart->build($this);

        return $this;
    }

    public function needTable($tableName)
    {
        if(! in_array($tableName, $this->neededTableNames))
        {
            $this->neededTableNames[] = $tableName;
        }

        return $this;
    }

    public function ensureNeededTablesArePresent(array $snippets)
    {
        foreach($this->neededTableNames as $tableName)
        {
            if(! $this->isAtLeastOneSnippetHasNeededTable($tableName, $snippets))
            {
                throw new \LogicException("One of query parts you used needs $tableName table");
            }
        }
    }

    private function isAtLeastOneSnippetHasNeededTable($tableName, array $snippets)
    {
        foreach($snippets as $snippet)
        {
            if(! $snippet instanceof NeedTableAware)
            {
                throw new \LogicException('Snippet has not expected NeedTableAware type');
            }

            if($snippet->hasNeededTable($tableName))
            {
                return true;
            }
        }

        return false;
    }
}
