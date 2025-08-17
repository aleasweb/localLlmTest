<?php

namespace Maze;

class MazeRenderer
{
    private array $maze;
    private int $cellSize;
    private array $colors;

    public function __construct(array $maze, int $cellSize = 20)
    {
        $this->maze = $maze;
        $this->cellSize = $cellSize;
        $this->colors = [
            'wall' => [0, 0, 0],      // Черный для стен
            'path' => [255, 255, 255], // Белый для проходов
            'start' => [0, 255, 0],    // Зеленый для входа
            'end' => [255, 0, 0],      // Красный для выхода
            'border' => [128, 128, 128] // Серый для границ
        ];
    }

    /**
     * Отрисовка лабиринта в текстовом виде
     */
    public function renderAsText(): string
    {
        $output = '';
        $height = count($this->maze);
        $width = count($this->maze[0]);

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if ($this->maze[$y][$x] === 1) {
                    $output .= '██'; // Стена
                } elseif ($x === 0 && $y === 1) {
                    $output .= 'S '; // Вход
                } elseif ($x === $width - 1 && $y === $height - 2) {
                    $output .= 'E '; // Выход
                } else {
                    $output .= '  '; // Проход
                }
            }
            $output .= "\n";
        }

        return $output;
    }

    /**
     * Отрисовка лабиринта в виде изображения PNG
     */
    public function renderAsImage(string $filename = null): string
    {
        $height = count($this->maze);
        $width = count($this->maze[0]);

        // Создаем изображение
        $imageWidth = $width * $this->cellSize;
        $imageHeight = $height * $this->cellSize;
        
        $image = imagecreate($imageWidth, $imageHeight);
        
        if (!$image) {
            throw new \RuntimeException('Не удалось создать изображение');
        }

        // Определяем цвета
        $wallColor = imagecolorallocate($image, ...$this->colors['wall']);
        $pathColor = imagecolorallocate($image, ...$this->colors['path']);
        $startColor = imagecolorallocate($image, ...$this->colors['start']);
        $endColor = imagecolorallocate($image, ...$this->colors['end']);
        $borderColor = imagecolorallocate($image, ...$this->colors['border']);

        // Отрисовываем лабиринт
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $pixelX = $x * $this->cellSize;
                $pixelY = $y * $this->cellSize;

                if ($this->maze[$y][$x] === 1) {
                    // Стена
                    imagefilledrectangle($image, $pixelX, $pixelY, 
                        $pixelX + $this->cellSize - 1, $pixelY + $this->cellSize - 1, $wallColor);
                } elseif ($x === 0 && $y === 1) {
                    // Вход
                    imagefilledrectangle($image, $pixelX, $pixelY, 
                        $pixelX + $this->cellSize - 1, $pixelY + $this->cellSize - 1, $startColor);
                } elseif ($x === $width - 1 && $y === $height - 2) {
                    // Выход
                    imagefilledrectangle($image, $pixelX, $pixelY, 
                        $pixelX + $this->cellSize - 1, $pixelY + $this->cellSize - 1, $endColor);
                } else {
                    // Проход
                    imagefilledrectangle($image, $pixelX, $pixelY, 
                        $pixelX + $this->cellSize - 1, $pixelY + $this->cellSize - 1, $pathColor);
                }

                // Добавляем границы для лучшей видимости
                imagerectangle($image, $pixelX, $pixelY, 
                    $pixelX + $this->cellSize - 1, $pixelY + $this->cellSize - 1, $borderColor);
            }
        }

        // Сохраняем изображение
        if ($filename === null) {
            $filename = 'maze_' . date('Y-m-d_H-i-s') . '.png';
        }

        if (!imagepng($image, $filename)) {
            throw new \RuntimeException('Не удалось сохранить изображение');
        }

        imagedestroy($image);

        return $filename;
    }

    /**
     * Отрисовка лабиринта в HTML с CSS
     */
    public function renderAsHtml(): string
    {
        $height = count($this->maze);
        $width = count($this->maze[0]);

        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Лабиринт</title>
    <style>
        .maze-container {
            display: inline-block;
            border: 2px solid #333;
            padding: 10px;
            background: #f0f0f0;
        }
        .maze-row {
            display: flex;
        }
        .maze-cell {
            width: ' . $this->cellSize . 'px;
            height: ' . $this->cellSize . 'px;
            border: 1px solid #ccc;
        }
        .wall {
            background-color: #000;
        }
        .path {
            background-color: #fff;
        }
        .start {
            background-color: #0f0;
        }
        .end {
            background-color: #f00;
        }
        .legend {
            margin-top: 20px;
            font-family: Arial, sans-serif;
        }
        .legend-item {
            display: inline-block;
            margin-right: 20px;
        }
        .legend-color {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 1px solid #333;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="maze-container">';

        for ($y = 0; $y < $height; $y++) {
            $html .= '<div class="maze-row">';
            for ($x = 0; $x < $width; $x++) {
                $class = 'maze-cell ';
                
                if ($this->maze[$y][$x] === 1) {
                    $class .= 'wall';
                } elseif ($x === 0 && $y === 1) {
                    $class .= 'start';
                } elseif ($x === $width - 1 && $y === $height - 2) {
                    $class .= 'end';
                } else {
                    $class .= 'path';
                }
                
                $html .= '<div class="' . $class . '"></div>';
            }
            $html .= '</div>';
        }

        $html .= '</div>
    <div class="legend">
        <div class="legend-item">
            <span class="legend-color" style="background-color: #000;"></span>
            <span>Стена</span>
        </div>
        <div class="legend-item">
            <span class="legend-color" style="background-color: #fff;"></span>
            <span>Проход</span>
        </div>
        <div class="legend-item">
            <span class="legend-color" style="background-color: #0f0;"></span>
            <span>Вход</span>
        </div>
        <div class="legend-item">
            <span class="legend-color" style="background-color: #f00;"></span>
            <span>Выход</span>
        </div>
    </div>
</body>
</html>';

        return $html;
    }

    /**
     * Установка размера ячейки
     */
    public function setCellSize(int $cellSize): void
    {
        $this->cellSize = $cellSize;
    }

    /**
     * Установка цветов
     */
    public function setColors(array $colors): void
    {
        $this->colors = array_merge($this->colors, $colors);
    }
} 