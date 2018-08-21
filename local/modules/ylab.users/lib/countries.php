<?php

namespace YLab\Users;

use Bitrix\Main\Entity\DataManager;

/**
 * Class CountriesTable
 * @package YLab\Users
 *
 * @author Alexander Shatalov
 * @see https://github.com/avshatalov48/bitrix-webinar/
 */
class CountriesTable extends DataManager
{
    /**
     * @return string
     */
    public static function getFilePath()
    {
        return __FILE__;
    }

    /**
     * @return string
     */
    public static function getTableName()
    {
        return "b_ylab_countries";
    }

    /**
     * @return array
     */
    public static function getMap()
    {
        return [
            "ID" => [
                "data_type" => "integer",
                "primary" => true,
                "autocomplete" => true,
            ],
            "NAME" => [
                "data_type" => "string",
                "required" => true
            ]
        ];
    }
}