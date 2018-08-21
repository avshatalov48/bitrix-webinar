<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \YLab\Users\UsersTable;
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
     * @param $arParams
     * @return array
     * @throws \Bitrix\Main\LoaderException
     */
    public function onPrepareComponentParams($arParams)
    {
        if (!Loader::includeModule("ylab.users")) {
            echo "<pre>" . Loc::getMessage("YLAB_USERS_MODULE_EXISTS") . "</pre>";
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
            $arUsers = UsersTable::getList([
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