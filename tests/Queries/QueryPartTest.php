<?php

use Muffin\Queries;
use Muffin\Conditions;
use Muffin\Types;
use Muffin\Tests\Fixtures\QueryParts\IsPony;
use Muffin\Tests\Fixtures\QueryParts\IsCute;
use Muffin\Tests\Escapers\SimpleEscaper;

class QueryPartTest extends PHPUnit_Framework_TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new SimpleEscaper();
    }

    public function testSelectQueryPart()
    {
        $query = (new Queries\Select())->setEscaper($this->escaper);
        $query
            ->select([ 'name', 'color' ])
            ->from('creatures')
            ->add(new IsPony())
            ->add(new IsCute())
            ->where(new Conditions\Equal(new Types\String('owner'), 'Paul'))
            ->having(new Conditions\Greater(new Types\Integer('rank'), 42))
        ;

        $this->assertSame("SELECT name, color FROM creatures WHERE type = 'pony' AND color = 'white' AND age < 1 AND owner = 'Paul' HAVING rank > 42", $query->toString());
    }

    public function testUpdateWithQueryPart()
    {
        $query = (new Queries\Update())->setEscaper($this->escaper);

        $query
            ->update('poney')
            ->set(array('owner' => 'John'))
            ->where(new Conditions\In(new Types\String('author'), array('julian', 'claude')))
            ->add(new IsCute())
        ;

        $this->assertSame("UPDATE poney SET owner = 'John' WHERE author IN ('julian', 'claude') AND color = 'white' AND age < 1", $query->toString($this->escaper));
    }

    public function testDeleteWithQueryPart()
    {
        $query = (new Queries\Delete('burger', 'b'))->setEscaper($this->escaper);

        $query
            ->where(new Conditions\Equal(new Types\String('owner'), 'Claude'))
            ->add(new IsCute());
        ;

        $this->assertSame("DELETE FROM burger AS b WHERE owner = 'Claude' AND color = 'white' AND age < 1", $query->toString($this->escaper));
    }
}