<?php

namespace Muffin\Queries;

use Muffin\Query;
use Muffin\Condition;
use Muffin\Traits\EscaperAware;
use Muffin\Snippet;
use Muffin\Queries\Snippets\Builders;
use Muffin\QueryPartAware;

class Update implements Query, QueryPartAware
{
    use
        EscaperAware,
        Builders\Join,
        Builders\Where,
        Builders\OrderBy,
        Builders\Limit,
        Builders\QueryPart;

    private
        $updatePart,
        $sets;

    public function __construct($table = null, $alias = null)
    {
        $this->updatePart = new Snippets\Update();
        $this->where = new Snippets\Where();
        $this->sets = new Snippets\Set();
        $this->orderBy = new Snippets\OrderBy();

        if(! empty($table))
        {
            $this->update($table, $alias);
        }
    }

    public function toString()
    {
        $snippets = $this->joins;
        $snippets[] = $this->updatePart;
        $this->ensureNeededTablesArePresent($snippets);

        $queryParts = array(
            $this->buildUpdate(),
            $this->buildJoin(),
            $this->buildSets(),
            $this->buildWhere($this->escaper),
            $this->buildOrderBy(),
            $this->buildLimit(),
        );

        return implode(' ', array_filter($queryParts));
    }

    public function update($table, $alias = null)
    {
        $this->updatePart->addTable($table, $alias);

        return $this;
    }

    public function set(array $fields)
    {
        $this->sets->set($fields);

        return $this;
    }

    private function buildUpdate()
    {
        $updateString = $this->updatePart->toString();

        if(empty($updateString))
        {
            throw new \RuntimeException('No table defined');
        }

        return $updateString;
    }

    private function buildSets()
    {
        $this->sets->setEscaper($this->escaper);

        return $this->sets->toString();
    }
}
