<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class Terminal extends TestCase
{
    /**
     * @var \Small\Terminal
     */
    protected $terminal;

    /**
     * @var string Символ отступа
     */
    protected $shiftSymbol = "  ";

    /**
     * @var string Приветственное сообщение
     */
    protected $message = "Hello, Tester";

    protected function setUp()
    {
        parent::setUp();

        $this->terminal = new \Small\Terminal([
            "shiftSymbol" => $this->shiftSymbol,
        ]);
    }

    /**
     * @throws Exception
     */
    public function test()
    {
        $this->assertInstanceOf(\Small\Terminal::class, $this->terminal);

        // output
        ob_start();
        $this->terminal->danger($this->message);
        $output = ob_get_contents();
        $this->assertEquals(PHP_EOL . $this->message, $output);
        ob_clean();

        $gj = "good job";
        $this->terminal->danger($this->message, $gj);
        $output = ob_get_contents();
        $this->assertEquals(PHP_EOL . "{$this->message} {$gj}", $output);
        ob_clean();

        $this->terminal->danger($this->message, $gj, 1);
        $output = ob_get_contents();
        $this->assertEquals(PHP_EOL . "{$this->message} {$gj} 1", $output);
        ob_end_clean();
    }

    /**
     * @throws Exception
     */
    public function testTabs()
    {
        ob_start();
        $this->assertInstanceOf(\Small\Terminal::class, $this->terminal->resetShift());
        $this->assertInstanceOf(\Small\Terminal::class, $this->terminal->shift());
        $this->terminal->warning($this->message);
        $output = ob_get_contents();
        $this->assertEquals(PHP_EOL . $this->shiftSymbol . $this->message, $output);
        $this->assertInstanceOf(\Small\Terminal::class, $this->terminal->unshift());
        ob_end_clean();
    }

    /**
     * @throws Exception
     */
    public function testChaining()
    {
        $this->assertInstanceOf(\Small\Terminal::class, $this->terminal
            ->shift()
            ->info('Info')
            ->success('Success')
            ->shift()
            ->warning('Warning')
            ->unshift()
            ->danger('Danger')
            ->critical('Critical')
            ->resetShift()
        );
    }

    /**
     * @throws Exception
     */
    public function testCritical()
    {
        ob_start();
        $this->terminal->critical('uP');
        $output = ob_get_contents();
        $this->assertEquals(PHP_EOL . mb_strtoupper("up", 'UTF-8'), $output);
        ob_clean();

        $this->terminal->critical('Вверх');
        $output = ob_get_contents();
        $this->assertEquals(PHP_EOL . mb_strtoupper("ВВЕРХ", 'UTF-8'), $output);
        ob_end_clean();
    }
}