# Техническая документация API

## Обзор архитектуры

Проект состоит из двух основных классов:

1. **MazeGenerator** - отвечает за генерацию лабиринта
2. **MazeRenderer** - отвечает за визуализацию лабиринта

## MazeGenerator

### Описание
Класс для генерации лабиринтов с использованием алгоритма рекурсивного обхода с возвратом.

### Конструктор
```php
public function __construct(int $width, int $height)
```

**Параметры:**
- `int $width` - ширина лабиринта (должна быть нечетной)
- `int $height` - высота лабиринта (должна быть нечетной)

**Исключения:**
- `InvalidArgumentException` - если размеры не являются нечетными числами

**Пример:**
```php
$generator = new MazeGenerator(21, 21);
```

### Методы

#### generate()
```php
public function generate(): array
```

Генерирует лабиринт и возвращает двумерный массив.

**Возвращает:** `array` - двумерный массив, где:
- `1` = стена
- `0` = проход

**Пример:**
```php
$maze = $generator->generate();
```

#### isSolvable()
```php
public function isSolvable(): bool
```

Проверяет проходимость лабиринта от входа к выходу с использованием алгоритма DFS.

**Возвращает:** `bool` - `true` если лабиринт проходим, `false` иначе

**Пример:**
```php
if ($generator->isSolvable()) {
    echo "Лабиринт проходим!";
} else {
    echo "Лабиринт непроходим!";
}
```

#### getDimensions()
```php
public function getDimensions(): array
```

Возвращает размеры сгенерированного лабиринта.

**Возвращает:** `array` с ключами:
- `width` - ширина лабиринта
- `height` - высота лабиринта

**Пример:**
```php
$dimensions = $generator->getDimensions();
echo "Размеры: {$dimensions['width']}x{$dimensions['height']}";
```

#### getMaze()
```php
public function getMaze(): array
```

Возвращает текущий лабиринт без повторной генерации.

**Возвращает:** `array` - двумерный массив лабиринта

## MazeRenderer

### Описание
Класс для визуализации лабиринтов в различных форматах.

### Конструктор
```php
public function __construct(array $maze, int $cellSize = 20)
```

**Параметры:**
- `array $maze` - двумерный массив лабиринта
- `int $cellSize` - размер ячейки в пикселях (по умолчанию 20)

**Пример:**
```php
$renderer = new MazeRenderer($maze, 15);
```

### Методы

#### renderAsText()
```php
public function renderAsText(): string
```

Создает текстовое представление лабиринта для консоли.

**Возвращает:** `string` - ASCII-представление лабиринта

**Символы:**
- `██` - стена
- `S ` - вход (зеленая ячейка)
- `E ` - выход (красная ячейка)
- `  ` - проход (пробелы)

**Пример:**
```php
echo $renderer->renderAsText();
```

#### renderAsImage()
```php
public function renderAsImage(string $filename = null): string
```

Создает PNG изображение лабиринта с использованием GD библиотеки.

**Параметры:**
- `string $filename` - имя файла (если не указано, генерируется автоматически)

**Возвращает:** `string` - имя сохраненного файла

**Исключения:**
- `RuntimeException` - если не удалось создать или сохранить изображение

**Пример:**
```php
$imageFile = $renderer->renderAsImage('my_maze.png');
echo "Изображение сохранено: $imageFile";
```

#### renderAsHtml()
```php
public function renderAsHtml(): string
```

Создает HTML представление лабиринта с CSS стилями.

**Возвращает:** `string` - HTML код с интерактивным лабиринтом

**Особенности:**
- Включает CSS стили для красивого отображения
- Содержит цветовую легенду
- Адаптивный дизайн

**Пример:**
```php
$html = $renderer->renderAsHtml();
file_put_contents('maze.html', $html);
```

#### setCellSize()
```php
public function setCellSize(int $cellSize): void
```

Устанавливает размер ячейки для всех форматов вывода.

**Параметры:**
- `int $cellSize` - размер ячейки в пикселях

**Пример:**
```php
$renderer->setCellSize(25); // Увеличить размер ячейки
```

#### setColors()
```php
public function setColors(array $colors): void
```

Настраивает цвета для отображения лабиринта.

**Параметры:**
- `array $colors` - массив цветов с ключами:
  - `wall` - цвет стен `[R, G, B]`
  - `path` - цвет проходов `[R, G, B]`
  - `start` - цвет входа `[R, G, B]`
  - `end` - цвет выхода `[R, G, B]`
  - `border` - цвет границ `[R, G, B]`

**Пример:**
```php
$renderer->setColors([
    'wall' => [0, 0, 0],      // Черный
    'path' => [255, 255, 255], // Белый
    'start' => [0, 255, 0],    // Зеленый
    'end' => [255, 0, 0],      // Красный
    'border' => [128, 128, 128] // Серый
]);
```

## Примеры использования

### Базовый пример
```php
<?php
require_once 'vendor/autoload.php';

use Maze\MazeGenerator;
use Maze\MazeRenderer;

// Создание и генерация лабиринта
$generator = new MazeGenerator(15, 15);
$maze = $generator->generate();

// Создание рендерера
$renderer = new MazeRenderer($maze, 20);

// Вывод в разных форматах
echo $renderer->renderAsText();
$renderer->renderAsImage('maze.png');
file_put_contents('maze.html', $renderer->renderAsHtml());
```

### Настройка внешнего вида
```php
<?php
// Настройка цветов
$renderer->setColors([
    'wall' => [50, 50, 50],     // Темно-серый
    'path' => [240, 240, 240],  // Светло-серый
    'start' => [0, 150, 0],     // Темно-зеленый
    'end' => [150, 0, 0],       // Темно-красный
    'border' => [100, 100, 100] // Серый
]);

// Настройка размера
$renderer->setCellSize(30);
```

### Проверка проходимости
```php
<?php
$generator = new MazeGenerator(21, 21);
$maze = $generator->generate();

if ($generator->isSolvable()) {
    echo "Лабиринт проходим!\n";
    
    // Получение статистики
    $dimensions = $generator->getDimensions();
    echo "Размеры: {$dimensions['width']}x{$dimensions['height']}\n";
} else {
    echo "Ошибка: лабиринт непроходим!\n";
}
```

## Обработка ошибок

### Проверка требований
```php
<?php
// Проверка версии PHP
if (version_compare(PHP_VERSION, '8.3.0', '<')) {
    die("Требуется PHP 8.3 или выше");
}

// Проверка расширения GD
if (!extension_loaded('gd')) {
    die("Требуется расширение GD");
}
```

### Обработка исключений
```php
<?php
try {
    $generator = new MazeGenerator(21, 21);
    $maze = $generator->generate();
    
    $renderer = new MazeRenderer($maze);
    $imageFile = $renderer->renderAsImage();
    
} catch (InvalidArgumentException $e) {
    echo "Ошибка параметров: " . $e->getMessage();
} catch (RuntimeException $e) {
    echo "Ошибка выполнения: " . $e->getMessage();
} catch (Exception $e) {
    echo "Неизвестная ошибка: " . $e->getMessage();
}
```

## Производительность

### Рекомендации по размерам
- **Малые лабиринты** (до 21x21): быстрая генерация и отображение
- **Средние лабиринты** (21x21 - 51x51): умеренная нагрузка
- **Большие лабиринты** (более 51x51): может потребоваться больше времени и памяти

### Оптимизация
- Используйте нечетные размеры для корректной работы алгоритма
- Для больших лабиринтов уменьшите размер ячейки в рендерере
- При создании множества изображений переиспользуйте объект рендерера 