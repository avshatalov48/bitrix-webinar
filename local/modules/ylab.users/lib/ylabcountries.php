<?php

namespace YLab\Users;

use Bitrix\Main\Entity;

/**
 * Class YlabCountriesTable
 * @package YLab\Users
 *
 * @author Alexander Shatalov
 * @see https://github.com/avshatalov48/bitrix-webinar/
 */
class YlabCountriesTable extends Entity\DataManager
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
     * @return array|Entity\public
     * @throws \Exception
     */
    public static function getMap()
    {
        return [
            new Entity\IntegerField("ID", [
                "primary" => true,
                "autocomplete" => true,
            ]),
            new Entity\StringField("NAME", [
                "required" => true,
            ]),
        ];
    }
}