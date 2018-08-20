<?php

use \YLab\Users\UsersTable;
use \Bitrix\Main\Localization\Loc;
use \YLab\Users\Helper;

/**
 * Формирование списка пользователей
 *
 * Class UsersListOrmComponent
 *
 * @author Alexander Shatalov
 * @see https://github.com/avshatalov48/bitrix-webinar/
 */
class UsersListOrmComponent extends \CBitrixComponent
{
    /**
     * Метод вызывается при инициализации класса
     *
     * @access public
     */
    public function executeComponent()
    {
        // Подключение модуля "ylab.users"
//        self::isIncludeModule('ylab.users');
        /** @var array $arResult */
        $this->arResult = $this->getUsersList();
        $this->includeComponentTemplate();
    }

    /**
     * Получение данных пользователей
     *
     * @access protected
     * @return array $arUsers
     */
    protected function getUsersList()
    {
        $arUsers = [];
        try {
            $arUsers = UsersTable::getList([
                "select" => [
                    "ID",
                    Loc::getMessage("FIELD_USER_NAME") => "USER_NAME",
                    Loc::getMessage("FIELD_TOWN_NAME") => "TOWN.NAME",
                    Loc::getMessage("FIELD_TOWN_REGION") => "TOWN.REGION",
                    Loc::getMessage("FIELD_DATE_BORN") => "DATE_BORN",
                    Loc::getMessage("FIELD_PHONE") => "PHONE"
                ]
            ])->fetchAll();
        } catch (\Exception $e) {
            Helper::parse($e->getMessage());
        }
        return $arUsers;
    }

    /**
     * Проверка подключения модулей
     * @param $sNameModule string Название модуля
     */
//    public static function isIncludeModule($sNameModule)
//    {
//        try {
//            Loader::includeModule($sNameModule);
//        } catch (\Exception $e) {
//            echo "<pre>";
//            print_r(Loc::getMessage($sNameModule));
//            print_r($e->getMessage());
//            echo "</pre>";
//            exit;
//        }
//    }
}