<?php

use PHPUnit\Framework\TestCase;
use Maze\MazeGenerator;

class MazeGeneratorTest extends TestCase
{
    private MazeGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = new MazeGenerator(11, 9);
    }

    public function testGenerateValidMaze(): void
    {
        $maze = $this->generator->generate();
        
        // Проверяем размеры лабиринта
        $this->assertEquals(9, count($maze));
        $this->assertEquals(11, count($maze[0]));
        
        // Проверяем что есть вход и выход
        $this->assertEquals(0, $maze[1][0]); // Вход
        $this->assertEquals(0, $maze[7][9]); // Выход
        
        // Проверяем что стены установлены корректно
        foreach ($maze as $row) {
            $this->assertContainsOnly([0, 1], $row);
        }
    }

    public function testMazeIsSolvable(): void
    {
        $this->generator->generate();
        $this->assertTrue($this->generator->isSolvable());
    }

    public function testGetDimensions(): void
    {
        $dimensions = $this->generator->getDimensions();
        $this->assertEquals(11, $dimensions['width']);
        $this->assertEquals(9, $dimensions['height']);
    }
}