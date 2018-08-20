<?php

namespace YLab\Validation\Components;

use \Bitrix\Main\Loader;
use \Bitrix\Main\HttpApplication;
use \Bitrix\Iblock\IblockTable;
use \Bitrix\Iblock\PropertyTable;
use \Bitrix\Iblock\PropertyEnumerationTable;
use YLab\Users\TownsTable;
use YLab\Validation\ComponentValidation;
use YLab\Validation\ValidatorHelper;

use \YLab\Users\UsersTable;
use \Bitrix\Main\Localization\Loc;
use \YLab\Users\Helper;


/**
 * Class ValidationUserFormOrmComponent
 * Компонент "Добавление пользователя с валидацией"
 *
 * @author Alexander Shatalov
 * @see https://github.com/avshatalov48/bitrix-webinar/
 *
 * @package YLab\Validation\Components
 */
class ValidationUserFormOrmComponent extends ComponentValidation
{
    /**
     * ValidationTestComponent constructor.
     * @param \CBitrixComponent|null $component
     * @param string $sFile
     * @throws \Bitrix\Main\IO\InvalidPathException
     * @throws \Bitrix\Main\SystemException
     * @throws \Exception
     */
    public function __construct(\CBitrixComponent $component = null, $sFile = __FILE__)
    {
        parent::__construct($component, $sFile);
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function executeComponent()
    {
        // Смысла в проверке подключения модулей большего нет, т.к. всё равно раньше вылетит ошибка с классами

        // Подключение модуля "ylab.users"
//        self::isIncludeModule('ylab.users');
        // Подключение модуля "ylab.validation"
//        self::isIncludeModule('ylab.validation');

        $this->arResult["FIELDS"] = $this->getUsersList();
        $this->arResult["TOWN_LIST"] = $this->getTownsList();
        dump($this->arResult);


        // Заносим значения placeholder и key fields в Loc
        exit;


        /**
         * Задание значений placeholder для полей
         */
        foreach ($this->arResult["FIELDS"] as $key => $arProperty) {
            $sPlaceHolder = "";
            if ($arProperty["CODE"] == "USER_NAME") {
                $sPlaceHolder = "Имя";
            }
            if ($arProperty["CODE"] == "DATE_BORN") {
                $sPlaceHolder = "ДД.ММ.ГГГГ";
            }
            if ($arProperty["CODE"] == "PHONE") {
                $sPlaceHolder = "+70000000000";
            }
            $this->arResult["PROPERTIES"][$key]["PLACEHOLDER"] = $sPlaceHolder;
        }

        /**
         * Валидация города
         */
        $this->oValidator->addExtension("town_exists", function ($attribute, $value, $parameters, $validator) {
            foreach ($this->arResult["TOWN_LIST"] as $arItem) {
                if ($arItem["XML_ID"] == $value) {
                    return true;
                }
            }
            return false;
        });

        /**
         * Непосредственно валидация и действия при успехе и фейле
         */
        if ($this->oRequest->isPost() && check_bitrix_sessid()) {
            $this->oValidator->setData($this->oRequest->toArray());

            $arRequest = HttpApplication::getInstance()->getContext()->getRequest();

            /**
             * Подстановка значений $_POST в форму, чтобы не сбрасывались при перезагрузке
             */
            $this->arResult["REQUEST"] = [
                "USER_NAME" => $arRequest["USER_NAME"],
                "DATE_BORN" => $arRequest["DATE_BORN"],
                "PHONE" => $arRequest["PHONE"],
                "TOWN_LIST" => $arRequest["TOWN_LIST"],
            ];

            if ($this->oValidator->passes()) {
                /**
                 * Запись пользователя в ИБ при успешной валидации
                 */


//                if ($oCIBlockElement->Add($arFields)) {
                $this->arResult["SUCCESS"] = true;
//                }
            } else {
                $this->arResult["ERRORS"] = ValidatorHelper::errorsToArray($this->oValidator);
            }
        }

        dump($this->arResult);
        exit;
        $this->includeComponentTemplate();
    }

    /**
     * Правила валидации
     * @return array
     */
    protected function rules()
    {
        /**
         * Перед формированием массива правил валидации мы можем вытащить все необходимые данные из различных источников
         */
        return [
            "USER_NAME" => "required",
            "DATE_BORN" => "required|date_format:d.m.Y",
            "PHONE" => [
                "required",
                "regex:/^\+7\d{10}$/",
            ],
            "TOWN_LIST" => "required|town_exists",
        ];
    }

    /**
     * Получение списка городов
     *
     * @access protected
     * @return array $arTowns
     */
    protected function getTownsList()
    {
        $arTowns = [];
        try {
            $arTowns = TownsTable::getList()->fetchAll();
        } catch (\Exception $e) {
            Helper::parse($e->getMessage());
        }
        return $arTowns;
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
                    "TOWN.REGION",
                    "DATE_BORN",
                    "PHONE"
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