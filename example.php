<?php

require_once 'vendor/autoload.php';

use Maze\MazeGenerator;
use Maze\MazeRenderer;

echo "=== Генератор лабиринта на PHP 8.3 ===\n\n";

// Проверяем версию PHP
if (version_compare(PHP_VERSION, '8.3.0', '<')) {
    echo "Ошибка: Требуется PHP 8.3 или выше. Текущая версия: " . PHP_VERSION . "\n";
    exit(1);
}

// Проверяем наличие GD расширения
if (!extension_loaded('gd')) {
    echo "Ошибка: Требуется расширение GD для создания изображений.\n";
    echo "Установите его командой: sudo apt-get install php8.3-gd (Ubuntu/Debian)\n";
    echo "или: brew install php@8.3 (macOS)\n";
    exit(1);
}

try {
    // Создаем генератор лабиринта (размер должен быть нечетным для корректной работы)
    $width = 21;  // Нечетное число
    $height = 21; // Нечетное число
    
    echo "Создаем лабиринт размером {$width}x{$height}...\n";
    
    $generator = new MazeGenerator($width, $height);
    $maze = $generator->generate();
    
    echo "Лабиринт сгенерирован!\n";
    
    // Проверяем проходимость
    if ($generator->isSolvable()) {
        echo "✓ Лабиринт проходим (от входа к выходу есть путь)\n";
    } else {
        echo "✗ Лабиринт непроходим!\n";
    }
    
    echo "\n=== Текстовая отрисовка ===\n";
    $renderer = new MazeRenderer($maze, 1);
    echo $renderer->renderAsText();
    
    echo "\n=== Создание изображения ===\n";
    $renderer->setCellSize(15); // Увеличиваем размер ячейки для лучшей видимости
    $imageFile = $renderer->renderAsImage();
    echo "Изображение сохранено как: {$imageFile}\n";
    
    echo "\n=== Создание HTML файла ===\n";
    $htmlContent = $renderer->renderAsHtml();
    $htmlFile = 'maze_' . date('Y-m-d_H-i-s') . '.html';
    file_put_contents($htmlFile, $htmlContent);
    echo "HTML файл сохранен как: {$htmlFile}\n";
    
    echo "\n=== Статистика лабиринта ===\n";
    $dimensions = $generator->getDimensions();
    echo "Размеры: {$dimensions['width']}x{$dimensions['height']}\n";
    
    // Подсчитываем количество стен и проходов
    $walls = 0;
    $paths = 0;
    foreach ($maze as $row) {
        foreach ($row as $cell) {
            if ($cell === 1) {
                $walls++;
            } else {
                $paths++;
            }
        }
    }
    
    echo "Стен: {$walls}\n";
    echo "Проходов: {$paths}\n";
    echo "Процент проходимости: " . round(($paths / ($walls + $paths)) * 100, 2) . "%\n";
    
    echo "\n=== Инструкции ===\n";
    echo "1. Откройте файл {$htmlFile} в браузере для интерактивного просмотра\n";
    echo "2. Изображение {$imageFile} можно открыть в любом графическом редакторе\n";
    echo "3. В текстовой версии: S = вход, E = выход, ██ = стена, пробелы = проход\n";
    
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
    exit(1);
} 