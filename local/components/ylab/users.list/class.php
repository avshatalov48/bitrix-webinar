<?php

use \Bitrix\Iblock\ElementTable;
use \Bitrix\Main\Loader;

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
     * Метод вызывается при инициализации класса
     *
     * @access public
     */
    public function executeComponent()
    {
        /** @global \CMain $APPLICATION */
        global $APPLICATION;

        /** Сброс буфера */
        // $APPLICATION->RestartBuffer();

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

        /**
         * Подключение модуля ИБ
         */
        try {
            Loader::includeModule("iblock");
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $this->arParams["IBLOCK_ID"] = $this->getIblockIdByCode($this->arParams["CODE"]);

        /**
         * Получение массива активных пользователей
         */
        try {
            $arUsers = ElementTable::getList(array(
                "select" => array("ID", "NAME"),
                "filter" => array("IBLOCK_ID" => $this->arParams["IBLOCK_ID"], "ACTIVE" => $this->arParams["ACTIVE"]),
                "order" => array("ID" => "ASC")
            ))->fetchAll();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return $arUsers;
    }

    /**
     * Получение "ID" ИБ по символьному коду
     * @access public
     * @param string $sIblockCode Символьный код ИБ
     * @return integer
     */
    public function getIblockIdByCode($sIblockCode)
    {
        try {
            return \Bitrix\Iblock\IblockTable::getList([
                "filter" => [
                    "CODE" => $sIblockCode,
                ],
            ])->fetch()["ID"];
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return false;
    }
}