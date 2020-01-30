<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class Script extends TestCase
{
    protected $scriptClass;

    protected function setUp()
    {
        parent::setUp();

        $this->scriptClass = new class('User') extends \Small\Script
        {
            function getName()
            {
                return $this->name;
            }

            function getArguments()
            {
                return $this->arguments;
            }

            /**
             * @return int
             */
            function getWorkTime()
            {
                return parent::getWorkTime();
            }

            function __construct(string $name)
            {
                parent::__construct($name);
            }
        };
    }

    /**
     * @throws Exception
     */
    public function test()
    {
        $this->assertInstanceOf(\Small\Script::class, $this->scriptClass);

        $this->assertEquals('User', $this->scriptClass->getName());

        $this->scriptClass->arguments(['verbose' => false]);
        $this->assertInstanceOf(\Small\Arguments::class, $this->scriptClass->getArguments());

        $this->assertNotNull($this->scriptClass->getWorkTime());
        $this->assertGreaterThanOrEqual(0, $this->scriptClass->getWorkTime());
    }
}