<?php

use \Bitrix\Iblock\ElementTable;
use \Bitrix\Main\Loader;
use YLab\Users\UsersTable;
use \Bitrix\Main\Localization\Loc;

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
        if (Loader::includeModule("ylab.users")) {
            $this->arResult = UsersTable::getList(array(
                "select" => array("ID", "USER_NAME", "TOWN", "DATE_BORN", "PHONE")
            ))->fetchAll();
        }
        // Сделать через while fetch обработку значений ключей, либо поискать функцию

        var_dump($this->arResult);
        exit;

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
         * Получение данных пользователей
         */
        $arSelect = [
            "ID",
            "IBLOCK_ID",
            "NAME",
            "PROPERTY_*"
        ];
        $arFilter = [
            "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
            "ACTIVE" => $this->arParams["ACTIVE"]
        ];
        $arSort = [
            "SORT" => "ASC"
        ];
        $res = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);

        $iOrder = 0;
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arUsers[$iOrder] = $arFields;
            $arProperties = $ob->GetProperties();
            $arUsers[$iOrder++]["PROPERTIES"] = $arProperties;
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