<?php

use Mdd\QueryBuilder\Queries;
use Mdd\QueryBuilder\Conditions;
use Mdd\QueryBuilder\Types;
use Mdd\QueryBuilder\Tests\Escapers\SimpleEscaper;

class UpdateTest extends PHPUnit_Framework_TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new SimpleEscaper();
    }

    public function testSimpleUpdateUsingConstructor()
    {
        $query = (new Queries\Update('burger'))->setEscaper($this->escaper);

        $query
            ->set(array('taste' => 'cheese'))
            ->set(array('vegan' => new Types\Boolean(false)))
            ->set(array('name' => 'The big one'))
        ;

        $this->assertSame("UPDATE burger SET taste = 'cheese', vegan = 0, name = 'The big one'", $query->toString($this->escaper));

        $query
            ->where(new Conditions\Greater('score', new Types\Integer(1337)))
            ->where(new Conditions\Equal('author', new Types\String('julian')))
        ;

        $this->assertSame("UPDATE burger SET taste = 'cheese', vegan = 0, name = 'The big one' WHERE score > 1337 AND author = 'julian'", $query->toString($this->escaper));
    }

    public function testSimpleUpdateUsingSetter()
    {
        $query = (new Queries\Update())->setEscaper($this->escaper);

        $query
            ->update('burger')
            ->set(array('date' => '2014-03-07 13:37:42'))
        ;

        $this->assertSame("UPDATE burger SET date = '2014-03-07 13:37:42'", $query->toString($this->escaper));
    }

    public function testUpdateMultipleTable()
    {
        $query = (new Queries\Update())->setEscaper($this->escaper);

        $query
            ->update('burger', 'b')
            ->update('poney', 'p')
            ->set(array('date' => '2014-03-07 13:37:42'))
            ->where(new Conditions\In('author', array(new Types\String('julian'), new Types\String('claude'))))
        ;

        $this->assertSame("UPDATE burger AS b, poney AS p SET date = '2014-03-07 13:37:42' WHERE author IN ('julian', 'claude')", $query->toString($this->escaper));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testUpdateWithoutTable()
    {
        $query = (new Queries\Update())->setEscaper($this->escaper);

        $query
            ->set(array('date' => '2014-03-07 13:37:42'))
        ;

        $query->toString($this->escaper);
    }
}
