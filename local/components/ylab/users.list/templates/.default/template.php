<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Web\Json;

/**
 * Вывод результатов
 * @var array $arResult
 */
try {
    echo Json::encode($this->getComponent()->arResult);
} catch (\Exception $e) {
    echo $e->getMessage();
}