<?php
/**
 * @const Путь к корневой директории
 */
define("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);

/**
 * @var string Путь к включаемому файлу
 */
$sPathFileName = ROOT_DIR . "/local/vendor/autoload.php";

/**
 * @throws \Exception
 */
if (file_exists($sPathFileName)) {
    require_once($sPathFileName);
} else {
    throw new \Exception('Не найден указанный файл');
}