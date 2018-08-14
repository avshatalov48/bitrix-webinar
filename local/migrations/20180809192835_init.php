<?php

use Phinx\Migration\AbstractMigration;
use \Bitrix\Main\Loader;
use \Bitrix\Iblock\PropertyTable;
use \Bitrix\Iblock\PropertyEnumerationTable;
use \Bitrix\Iblock\IblockTable;


/**
 * Class Init
 * Стартовая миграция - Создание типа ИБ "Служебный" + ИБ "Пользователи" со свойствами
 *
 * @author Alexander Shatalov
 * @see https://github.com/avshatalov48/bitrix-webinar/
 */
class Init extends AbstractMigration
{
    /**
     * @var array $arTypeIblock массив типа ИБ
     */
    public $arTypeIblock = [
        "ID" => "service",
        "SECTIONS" => "N",
        "IN_RSS" => "N",
        "SORT" => 100,
        "LANG" => [
            "ru" => [
                "NAME" => "Служебный"
            ],
            "en" => [
                "NAME" => "Service"
            ]
        ]
    ];

    /**
     * @var array $arIblock массив ИБ
     */
    public $arIblock = [
        "SITE_ID" => ["s1"],
        "ID" => 0,
        "CODE" => "users",
        "NAME" => "Пользователи",
    ];

    /**
     * @var array $arUserNameProperty свойство "Имя"
     */
    public $arUserNameProperty = [
        "CODE" => "USER_NAME",
        "NAME" => "Имя",
    ];

    /**
     * @var array $arDateBornProperty свойство "Дата рождения"
     */
    public $arDateBornProperty = [
        "CODE" => "DATE_BORN",
        "NAME" => "Дата рождения",
    ];

    /**
     * @var array $arPhoneProperty свойство "Номер телефона"
     */
    public $arPhoneProperty = [
        "CODE" => "PHONE",
        "NAME" => "Номер телефона",
    ];

    /**
     * @var array $arTownProperty свойство "Город"
     */
    public $arTownProperty = [
        "CODE" => "TOWN_LIST",
        "NAME" => "Город",
        "VALUES" => [
            "77" => "Москва",
            "78" => "Санкт-Петербург",
            "16" => "Казань",
        ]
    ];

    /**
     * Накатываем миграцию - "vendor\bin\phinx migrate"
     * @throws Exception
     * @access public
     */
    public function up()
    {
        /**
         * Подключение модуля ИБ
         */
        try {
            Loader::includeModule("iblock");
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        /**
         * Проверка существования типа ИБ с необходимым ID
         */

        $arIblocksType = \CIBlockType::GetList(
            [],
            ["ID" => $this->arTypeIblock["ID"]]
        );

        if (!$arIblocksType->fetch()) {
            /**
             * Создание типа ИБ
             */
            $obIBlockType = new CIBlockType;
            if(!$obIBlockType->Add($this->arTypeIblock)){
                echo $obIBlockType->LAST_ERROR;
                return;
            }
        }

        /**
         * Проверка существования ИБ с необходимым символьным кодом
         */
        try {
            $arIblocks = IblockTable::getList([
                "filter" => [
                    "CODE" => $this->arIblock["CODE"],
                ],
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        if (!$arIblocks->fetch()) {
            /**
             * Создание ИБ
             */
            $oIblock = new CIBlock();
            $this->arIblock["ID"] = $oIblock->Add([
                "NAME" => $this->arIblock["NAME"],
                "CODE" => $this->arIblock["CODE"],
                "ACTIVE" => "Y",
                "IBLOCK_TYPE_ID" => $this->arTypeIblock["ID"],
                "SITE_ID" => $this->arIblock["SITE_ID"],
            ]);
        }

        /**
         * Создание свойства "Имя"
         */
        PropertyTable::add([
            "IBLOCK_ID" => $this->arIblock["ID"],
            "NAME" => $this->arUserNameProperty["NAME"],
            "CODE" => $this->arUserNameProperty["CODE"],
            "ACTIVE" => "Y",
            "PROPERTY_TYPE" => "S",
            "MULTIPLE" => "N",
            "IS_REQUIRED" => "Y",
        ]);

        /**
         * Создание свойства "Дата рождения"
         */
        PropertyTable::add([
            "IBLOCK_ID" => $this->arIblock["ID"],
            "NAME" => $this->arDateBornProperty["NAME"],
            "CODE" => $this->arDateBornProperty["CODE"],
            "ACTIVE" => "Y",
            "PROPERTY_TYPE" => "S",
            "MULTIPLE" => "N",
            "IS_REQUIRED" => "Y",
            "USER_TYPE" => "Date",
        ]);

        /**
         * Создание свойства "Номер телефона"
         */
        PropertyTable::add([
            "IBLOCK_ID" => $this->arIblock["ID"],
            "NAME" => $this->arPhoneProperty["NAME"],
            "CODE" => $this->arPhoneProperty["CODE"],
            "ACTIVE" => "Y",
            "PROPERTY_TYPE" => "S",
            "MULTIPLE" => "N",
            "IS_REQUIRED" => "Y",
        ]);

        /**
         * Создание свойства "Список городов"
         */
        $iTownPropertyId = PropertyTable::add([
            "IBLOCK_ID" => $this->arIblock["ID"],
            "NAME" => $this->arTownProperty["NAME"],
            "CODE" => $this->arTownProperty["CODE"],
            "ACTIVE" => "Y",
            "PROPERTY_TYPE" => "L",
            "MULTIPLE" => "N",
            "IS_REQUIRED" => "Y",
        ])->getId();

        /**
         * Наполнение вариантами значений "Список городов"
         */
        foreach ($this->arTownProperty["VALUES"] as $iTownPropertyKey => $sTownPropertyValue) {
            PropertyEnumerationTable::add([
                "PROPERTY_ID" => $iTownPropertyId,
                "VALUE" => $sTownPropertyValue,
                "XML_ID" => $iTownPropertyKey,
            ]);
        }
    }

    /**
     * Откатываем миграцию - "vendor\bin\phinx rollback"
     * @throws Exception
     * @access public
     */
    public function down()
    {
        /**
         * Подключение модуля ИБ
         */
        try {
            Loader::includeModule("iblock");
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        /**
         * Удаление всех ИБ с необходимым символьным кодом
         */
        try {
            $arIblocks = IblockTable::getList([
                "filter" => [
                    "CODE" => $this->arIblock["CODE"],
                ],
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        while ($arItem = $arIblocks->fetch()) {
            CIBlock::Delete($arItem["ID"]);
        }

        /**
         * Удаление типа ИБ
         */
        CIBlockType::Delete($this->arTypeIblock["ID"]);
    }
}