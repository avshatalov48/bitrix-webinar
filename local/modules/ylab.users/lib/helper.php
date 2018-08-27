<?php

namespace YLab\Users;

use \Bitrix\Main\Localization\Loc;

/**
 * Class Helper
 * @package YLab\Users
 */
class Helper
{
    /**
     * Вывод удобочитаемой информации в форматированном виде
     * @param $expression
     */
    public static function parse($expression)
    {
        echo "<pre>";
        print_r($expression);
        echo "</pre>";
    }

    /**
     * Возвращает по коду соответствующее сообщение на текущем языке.
     * Единый языковой файл для компонентов с одинаковыми кодами.
     * Меньше дублирования.
     *
     * @param $sCode string Код
     * @return string Сообщение
     */
    public static function i18n($sCode)
    {
        return Loc::getMessage($sCode);
    }
}