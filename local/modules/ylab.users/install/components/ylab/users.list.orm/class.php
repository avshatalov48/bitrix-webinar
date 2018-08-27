<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

use \YLab\Users\YlabUsersTable;
use \YLab\Users\Helper;

/**
 * Class UsersListOrmComponent
 * Формирование списка пользователей ORM
 *
 * @author Alexander Shatalov
 * @see https://github.com/avshatalov48/bitrix-webinar/
 */
class UsersListOrmComponent extends \CBitrixComponent
{
    /**
     * @param $arParams
     * @return array
     * @throws \Bitrix\Main\LoaderException
     */
    public function onPrepareComponentParams($arParams)
    {
        if (!Loader::includeModule("ylab.users")) {
            echo "<pre>";
            print_r(Loc::getMessage("YLAB_USERS_MODULE_EXISTS"));
            echo "</pre>";
            exit;
        }
        return parent::onPrepareComponentParams($arParams);
    }

    /**
     * Метод вызывается при инициализации класса
     *
     * @access public
     */
    public function executeComponent()
    {
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
            $arUsers = YlabUsersTable::getList([
                "select" => [
                    "ID",
                    "USER_NAME",
                    "TOWN.NAME",
                    "TOWN.COUNTRY.NAME",
                    "DATE_BORN",
                    "PHONE"
                ],
                "order" => ["ID" => "ASC"]
            ])->fetchAll();
        } catch (\Exception $e) {
            Helper::parse($e->getMessage());
        }
        return $arUsers;
    }
}