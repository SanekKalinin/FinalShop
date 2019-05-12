<?php

/**
 * Функция для автоматического подключения классов
 */
spl_autoload_register(function ($class_name)
{
    // Массив папок, в которых могут находиться необходимые классы
    $array_paths = array(
        '/framework/',
        '/app/models/',
        '/app/components/',
        '/app/controllers/',
    );

    // Проходим по массиву папок
    foreach ($array_paths as $path) {

        // Формируем имя и путь к файлу с классом
        $path = BASEPATH . $path . $class_name . '.php';

        // Если такой файл существует, подключаем его
        if (is_file($path)) {
            include_once $path;
            return;
        }
    }
});
