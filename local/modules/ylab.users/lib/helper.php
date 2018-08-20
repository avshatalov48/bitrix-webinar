<?php

namespace YLab\Users;

/**
 * Class Helper
 * @package YLab\Users
 */
class Helper
{
    /*
     * Вывод удобочитаемой информации в форматированном виде
     */
    public static function parse($expression)
    {
        echo "<pre>";
        print_r($expression);
        echo "</pre>";
    }
}