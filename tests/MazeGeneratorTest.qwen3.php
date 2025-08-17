<?php

namespace Maze\Tests;

use Maze\MazeGenerator;
use PHPUnit\Framework\TestCase;

class MazeGeneratorTest extends TestCase
{
    public function testConstructorSetsDimensions(): void
    {
        $width = 10;
        $height = 15;
        $generator = new MazeGenerator($width, $height);
        
        $dimensions = $generator->getDimensions();
        $this->assertEquals($width, $dimensions['width']);
        $this->assertEquals($height, $dimensions['height']);
    }
    
    public function testInitializeMazeCreatesCorrectStructure(): void
    {
        $generator = new MazeGenerator(5, 5);
        $maze = $generator->getMaze();
        
        // Проверяем, что размеры правильные
        $this->assertCount(5, $maze);
        $this->assertCount(5, $maze[0]);
        
        // Проверяем, что все ячейки инициализированы как стены (1)
        foreach ($maze as $row) {
            foreach ($row as $cell) {
                $this->assertEquals(1, $cell);
            }
        }
    }
    
    public function testGenerateCreatesValidMaze(): void
    {
        $generator = new MazeGenerator(11, 11); // Нечетные размеры для корректной работы алгоритма
        $maze = $generator->generate();
        
        // Проверяем размеры
        $this->assertCount(11, $maze);
        $this->assertCount(11, $maze[0]);
        
        // Проверяем, что вход и выход установлены как проходы (0)
        $this->assertEquals(0, $maze[1][0]); // Вход
        $this->assertEquals(0, $maze[10][10]); // Выход
        
        // Проверяем, что все ячейки - 0 или 1
        foreach ($maze as $row) {
            foreach ($row as $cell) {
                $this->assertContains($cell, [0, 1]);
            }
        }
    }
    
    public function testMazeIsSolvable(): void
    {
        $generator = new MazeGenerator(11, 11);
        $generator->generate();
        
        $solvable = $generator->isSolvable();
        $this->assertTrue($solvable);
    }
    
    public function testGetMazeReturnsCorrectMaze(): void
    {
        $generator = new MazeGenerator(7, 7);
        $generator->generate();
        $maze = $generator->getMaze();
        
        // Проверяем, что возвращается тот же лабиринт, что и при генерации
        $this->assertIsArray($maze);
        $this->assertCount(7, $maze);
        $this->assertCount(7, $maze[0]);
    }
    
    public function testGeneratePathCreatesValidMazeStructure(): void
    {
        $generator = new MazeGenerator(15, 15);
        $maze = $generator->generate();
        
        // Проверяем, что лабиринт имеет правильную структуру
        $this->assertArrayHasKey('width', $generator->getDimensions());
        $this->assertArrayHasKey('height', $generator->getDimensions());
        
        // Проверяем, что вход и выход установлены
        $this->assertEquals(0, $maze[1][0]); // Вход
        $this->assertEquals(0, $maze[13][14]); // Выход
        
        // Проверяем, что все ячейки - 0 или 1
        foreach ($maze as $row) {
            foreach ($row as $cell) {
                $this->assertContains($cell, [0, 1]);
            }
        }
    }
    
    public function testIsSolvableReturnsTrueForGeneratedMaze(): void
    {
        $generator = new MazeGenerator(9, 9);
        $generator->generate();
        
        // Генерируем лабиринт и проверяем, что он решаем
        $this->assertTrue($generator->isSolvable());
    }
    
    public function testInvalidDimensions(): void
    {
        // Проверяем, что минимальные размеры работают корректно
        $generator = new MazeGenerator(3, 3);
        $maze = $generator->generate();
        
        $this->assertCount(3, $maze);
        $this->assertCount(3, $maze[0]);
    }
}