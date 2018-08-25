<?php

namespace YLab\Users;

use Bitrix\Main\Entity;

/**
 * Class YlabUsersTable
 * @package YLab\Users
 *
 * @author Alexander Shatalov
 * @see https://github.com/avshatalov48/bitrix-webinar/
 */
class YlabUsersTable extends Entity\DataManager
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
        return "b_ylab_users";
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
            "USER_NAME" => [
                "data_type" => "string",
                "required" => true
            ],
            "TOWN_ID" => [
                "data_type" => "integer",
                "required" => true
            ],
            new Entity\ReferenceField(
                "TOWN",
                "YLab\Users\YlabTowns",
                ["=this.TOWN_ID" => "ref.ID"]
            ),
            "DATE_BORN" => [
                "data_type" => "date",
                "required" => true
            ],
            "PHONE" => [
                "data_type" => "string",
                "required" => true
            ],
        ];
    }
}