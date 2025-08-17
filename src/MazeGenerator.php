<?php

namespace Maze;

/**
 * Класс MazeGenerator генерирует лабиринты заданного размера, используя алгоритм глубокого первооткрывателя (DFS).
 * Лабиринт представляет собой 2D-массив, где 1 означает стену, а 0 — проход. Класс также проверяет, решаем ли лабиринт.
 */
class MazeGenerator
{
    private int $width;
    private int $height;
    private array $maze;
    private array $visited;

    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;
        $this->initializeMaze();
    }

    private function initializeMaze(): void
    {
        // Создаем лабиринт, где 1 = стена, 0 = проход
        $this->maze = array_fill(0, $this->height, array_fill(0, $this->width, 1));
        $this->visited = array_fill(0, $this->height, array_fill(0, $this->width, false));
    }

    public function generate(): array
    {
        // Начинаем с ячейки (1, 1) - нечетные координаты
        $this->generatePath(1, 1);
        
        // Устанавливаем вход и выход
        $this->maze[1][0] = 0; // Вход
        $this->maze[$this->height - 2][$this->width - 1] = 0; // Выход
        
        return $this->maze;
    }

    private function generatePath(int $x, int $y): void
    {
        $this->visited[$y][$x] = true;
        $this->maze[$y][$x] = 0;

        // Направления: вверх, вправо, вниз, влево
        $directions = [
            [0, -2], // вверх
            [2, 0],  // вправо
            [0, 2],  // вниз
            [-2, 0]  // влево
        ];

        // Перемешиваем направления для случайности
        shuffle($directions);

        foreach ($directions as [$dx, $dy]) {
            $newX = $x + $dx;
            $newY = $y + $dy;

            // Проверяем границы и посещенность
            if ($this->isValidCell($newX, $newY) && !$this->visited[$newY][$newX]) {
                // Пробиваем стену между текущей и новой ячейкой
                $this->maze[$y + $dy / 2][$x + $dx / 2] = 0;
                $this->generatePath($newX, $newY);
            }
        }
    }

    private function isValidCell(int $x, int $y): bool
    {
        return $x > 0 && $x < $this->width - 1 && $y > 0 && $y < $this->height - 1;
    }

    public function getMaze(): array
    {
        return $this->maze;
    }

    public function getDimensions(): array
    {
        return ['width' => $this->width, 'height' => $this->height];
    }

    public function isSolvable(): bool
    {
        $start = [1, 0]; // Вход
        $end = [$this->width - 2, $this->height - 1]; // Выход
        
        return $this->findPath($start[0], $start[1], $end[0], $end[1]);
    }

    private function findPath(int $startX, int $startY, int $endX, int $endY): bool
    {
        $visited = array_fill(0, $this->height, array_fill(0, $this->width, false));
        return $this->dfs($startX, $startY, $endX, $endY, $visited);
    }

    private function dfs(int $x, int $y, int $endX, int $endY, array &$visited): bool
    {
        // Проверяем границы
        if ($x < 0 || $x >= $this->width || $y < 0 || $y >= $this->height) {
            return false;
        }

        // Проверяем, что это проход и не посещена
        if ($this->maze[$y][$x] === 1 || $visited[$y][$x]) {
            return false;
        }

        // Достигли цели
        if ($x === $endX && $y === $endY) {
            return true;
        }

        $visited[$y][$x] = true;

        // Проверяем все направления
        $directions = [[0, 1], [1, 0], [0, -1], [-1, 0]];
        
        foreach ($directions as [$dx, $dy]) {
            if ($this->dfs($x + $dx, $y + $dy, $endX, $endY, $visited)) {
                return true;
            }
        }

        return false;
    }
} 