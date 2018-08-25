<?php

namespace YLab\Users;

use Bitrix\Main\Entity;

/**
 * Class YlabTownsTable
 * @package YLab\Users
 *
 * @author Alexander Shatalov
 * @see https://github.com/avshatalov48/bitrix-webinar/
 */
class YlabTownsTable extends Entity\DataManager
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
     * @throws \Bitrix\Main\ArgumentException
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
            ],
            "COUNTRY_ID" => [
                "data_type" => "integer",
                "required" => true
            ],
            new Entity\ReferenceField(
                "COUNTRY",
                "YLab\Users\YlabCountries",
                ["=this.COUNTRY_ID" => "ref.ID"]
            ),
        ];
    }
}