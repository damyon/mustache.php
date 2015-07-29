<?php

/**
 * @group inheritance
 * @group functional
 */
class Mustache_Test_Functional_LamdaArgumentTest extends PHPUnit_Framework_TestCase
{
    private $mustache;

    public function setUp()
    {
        $this->mustache = new Mustache_Engine(array(
            'pragmas' => array(Mustache_Engine::PRAGMA_LAMBDA_ARGS),
        ));
    }

    public function orHelper($text, $helper, $args) {
        foreach ($args as $arg) {
            if (!empty($arg)) {
                return $helper->render($text);
            }
        }
        return '';
    }

    public function boldHelper($text, $helper=false, $args=false) {
        return '<b>' . implode($text, '') . '</b>';
    }

    public function testLamdaFunctionsWithArgs()
    {
        $tpl = $this->mustache->loadTemplate(
            'Start of template {{#or a b}}a or b was true{{/or}} end of template'
        );

        $this->mustache->addHelper('or', array($this, 'orHelper'));

        $data = array('a' => false, 'b' => true);

        $this->assertEquals('Start of template a or b was true end of template', $tpl->render($data));

        $data = array('a' => true, 'b' => true);

        $this->assertEquals('Start of template a or b was true end of template', $tpl->render($data));

        $data = array('a' => true, 'b' => false);

        $this->assertEquals('Start of template a or b was true end of template', $tpl->render($data));

        $data = array('a' => false, 'b' => false);

        $this->assertEquals('Start of template  end of template', $tpl->render($data));
    }

    public function testLamdaFunctionsWithVar()
    {
        $this->mustache->addHelper('bold', array($this, 'boldHelper'));

        $data = array('b' => 'some text');

        $tpl = $this->mustache->loadTemplate(
            'Start of template {{{bold b}}} end of template'
        );
        $this->assertEquals('Start of template <b>some text</b> end of template', $tpl->render($data));

    }
}
