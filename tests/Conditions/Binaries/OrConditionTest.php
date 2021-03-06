<?php

use Muffin\Conditions;
use Muffin\Types;
use Muffin\Tests\Escapers\SimpleEscaper;

class OrConditionTest extends PHPUnit_Framework_TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new SimpleEscaper();
    }

    /**
     * @dataProvider providerTestOrCondition
     */
    public function testOrCondition($expected, $isEmpty, $conditionA, $conditionB)
    {
        $condition = new Conditions\Binaries\OrCondition($conditionA, $conditionB);

        $this->assertSame($expected, $condition->toString($this->escaper));
        $this->assertSame($isEmpty, $condition->isEmpty());
    }

    public function providerTestOrCondition()
    {
        $emptyCondition = new Conditions\NullCondition();
        $conditionA = new Conditions\Equal(new Types\String('name'), 'rainbow');
        $conditionB = new Conditions\Equal(new Types\String('taste'), 'burger');
        $conditionC = new Conditions\Equal(new Types\Integer('rank'), '42');
        $conditionD = new Conditions\Equal(new Types\String('author'),'julian');

        $orComposite1  = new Conditions\Binaries\OrCondition($conditionA, $conditionB);
        $orComposite2  = new Conditions\Binaries\OrCondition($conditionC, $conditionD);

        $andComposite1 = new Conditions\Binaries\AndCondition($conditionA, $conditionB);
        $andComposite2 = new Conditions\Binaries\AndCondition($conditionC, $conditionD);

        return array(
            'simple + simple' => array("name = 'rainbow' OR taste = 'burger'", false, $conditionA, $conditionB),
            'simple + empty'  => array("name = 'rainbow'", false, $conditionA, $emptyCondition),
            'empty + empty'  => array('', true, $emptyCondition, $emptyCondition),

            'OrComposite || empty'       => array("name = 'rainbow' OR taste = 'burger'", false, $orComposite1, $emptyCondition),
            'OrComposite || condition'   => array("(name = 'rainbow' OR taste = 'burger') OR rank = 42", false, $orComposite1, $conditionC),
            'OrComposite || OrComposite' => array("(name = 'rainbow' OR taste = 'burger') OR (rank = 42 OR author = 'julian')", false, $orComposite1, $orComposite2),

            'AndComposite || empty'        => array("name = 'rainbow' AND taste = 'burger'", false, $andComposite1, $emptyCondition),
            'AndComposite || condition'    => array("(name = 'rainbow' AND taste = 'burger') OR rank = 42", false, $andComposite1, $conditionC),
            'AndComposite || AndComposite' => array("(name = 'rainbow' AND taste = 'burger') OR (rank = 42 AND author = 'julian')", false, $andComposite1, $andComposite2),
        );
    }
}