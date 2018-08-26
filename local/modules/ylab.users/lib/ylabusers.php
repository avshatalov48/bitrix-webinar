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
            new Entity\StringField("USER_NAME", [
                "required" => true,
            ]),
            new Entity\IntegerField("TOWN_ID", [
                "required" => true,
            ]),
            new Entity\ReferenceField(
                "TOWN",
                "YLab\Users\YlabTowns",
                ["=this.TOWN_ID" => "ref.ID"]
            ),
            new Entity\DateField("DATE_BORN", [
                "required" => true,
            ]),
            new Entity\StringField("PHONE", [
                "required" => true,
            ]),
        ];
    }
}