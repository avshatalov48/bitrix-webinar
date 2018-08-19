<?php

namespace YLab\Users;

use Bitrix\Main\Entity\DataManager;

/**
 * Class TownsTable
 * @package YLab\Users
 */
class TownsTable extends DataManager
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
        return "b_ylab_towns";
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
            "TOWN" => [
                "data_type" => "string",
                "required" => true
            ],
            "REGION" => [
                "data_type" => "integer",
                "required" => true
            ]
        ];
    }
}