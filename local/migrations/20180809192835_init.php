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
            print_r($this->translit($e->getMessage()));
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
            if (!$obIBlockType->Add($this->arTypeIblock)) {
                print_r($this->translit($obIBlockType->LAST_ERROR));
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
            print_r($this->translit($e->getMessage()));
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
         * Наполнение вариантами значений "Список городов", получение их ID
         */
        $arTownProperties = [];
        foreach ($this->arTownProperty["VALUES"] as $iTownPropertyKey => $sTownPropertyValue) {
            $arTownProperties[] = PropertyEnumerationTable::add([
                "PROPERTY_ID" => $iTownPropertyId,
                "VALUE" => $sTownPropertyValue,
                "XML_ID" => $iTownPropertyKey,
            ])->getId();
        }

        /*
         * Наполнение тестовыми данными
         */
        $this->addUser("Вася", "01.01.1981", "+79210000001", $arTownProperties[0]);
        $this->addUser("Петя", "02.02.1982", "+79210000002", $arTownProperties[1]);
        $this->addUser("Кирюша", "03.03.1983", "+79210000003", $arTownProperties[2]);
    }

    /*
     * Добавление нового пользователя в ИБ
     *
     * @access public
     * @param string $sUserName Имя пользователя
     * @param string $sDateBorn Дата рождения
     * @param string $sPhone Телефон
     * @param integer $iTownList Город
     */
    public function addUser($sUserName, $sDateBorn, $sPhone, $iTownList)
    {
        $oCIBlockElement = new \CIBlockElement;

        $sName = "{$sUserName}|{$sDateBorn}|{$sPhone}";

        $arFields = [
            "IBLOCK_ID" => $this->arIblock["ID"],
            "NAME" => $sName,
            "PROPERTY_VALUES" => [
                "USER_NAME" => $sUserName,
                "DATE_BORN" => $sDateBorn,
                "PHONE" => $sPhone,
                "TOWN_LIST" => $iTownList,
            ],
        ];

        if (!$oCIBlockElement->Add($arFields)) {
            print_r($this->translit($oCIBlockElement->LAST_ERROR));
        };
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
            print_r($this->translit($e->getMessage()));
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
            print_r($this->translit($e->getMessage()));
        }

        while ($arItem = $arIblocks->fetch()) {
            CIBlock::Delete($arItem["ID"]);
        }

        /**
         * Удаление типа ИБ
         */
        CIBlockType::Delete($this->arTypeIblock["ID"]);
    }

    /*
     * Транслитерация
     *
     * @access public
     * @param string $sString Текст
     * @return string
     */
    public function translit($sString)
    {
        $arReplace = [
            "'" => "",
            "`" => "",
            "а" => "a", "А" => "A",
            "б" => "b", "Б" => "B",
            "в" => "v", "В" => "V",
            "г" => "g", "Г" => "G",
            "д" => "d", "Д" => "D",
            "е" => "e", "Е" => "E",
            "ё" => "e", "Ё" => "E",
            "ж" => "zh", "Ж" => "ZH",
            "з" => "z", "З" => "Z",
            "и" => "i", "И" => "I",
            "й" => "y", "Й" => "Y",
            "к" => "k", "К" => "K",
            "л" => "l", "Л" => "L",
            "м" => "m", "М" => "M",
            "н" => "n", "Н" => "N",
            "о" => "o", "О" => "O",
            "п" => "p", "П" => "P",
            "р" => "r", "Р" => "R",
            "с" => "s", "С" => "S",
            "т" => "t", "Т" => "T",
            "у" => "u", "У" => "U",
            "ф" => "f", "Ф" => "F",
            "х" => "h", "Х" => "H",
            "ц" => "c", "Ц" => "C",
            "ч" => "ch", "Ч" => "CH",
            "ш" => "sh", "Ш" => "SH",
            "щ" => "sch", "Щ" => "SCH",
            "ъ" => "", "Ъ" => "",
            "ы" => "y", "Ы" => "Y",
            "ь" => "", "Ь" => "",
            "э" => "e", "Э" => "E",
            "ю" => "yu", "Ю" => "YU",
            "я" => "ya", "Я" => "YA",
            "і" => "i", "І" => "I",
            "ї" => "yi", "Ї" => "YI",
            "є" => "e", "Є" => "E"
        ];
        return iconv("UTF-8", "UTF-8//IGNORE", strtr($sString, $arReplace));
    }
}