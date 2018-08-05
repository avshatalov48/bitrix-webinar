<?php

use \Bitrix\Iblock\ElementTable;
use \Bitrix\Main\Loader;

try {
    Loader::includeModule("iblock");
} catch (\Exception $e) {
    echo $e->getMessage();
}

/**
 * Формирование списка активных пользователей
 *
 * Class UsersListComponent
 *
 * @author Alexander Shatalov
 * @see https://github.com/avshatalov48/bitrix-webinar/
 */
class UsersListComponent extends \CBitrixComponent
{
    /**
     * @access protected
     * @var int ID инфоблока "Пользователи"
     */
    protected $iBlockId = 1;

    /**
     * Метод вызывается при инициализации класса
     *
     * @access public
     */
    public function executeComponent()
    {
        /** @global \CMain $APPLICATION */
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var array $arResult */
        $this->arResult = $this->getUsersList();

        $this->includeComponentTemplate();
    }

    /**
     * Получение массива активных пользователей
     *
     * @access protected
     * @return array $arUsers
     */
    protected function getUsersList()
    {
        $arUsers = [];
        try {
            $arUsers = ElementTable::getList(array(
                "select" => array("ID", "NAME"),
                "filter" => array("IBLOCK_ID" => $this->iBlockId, "ACTIVE" => "Y"),
                "order" => array("ID" => "ASC")
            ))->fetchAll();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return $arUsers;
    }
}