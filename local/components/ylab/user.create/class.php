<?php

namespace YLab\Validation\Components;

use \Bitrix\Main\Loader;
use \Bitrix\Main\HttpApplication;
use \Bitrix\Iblock\IblockTable;
use \Bitrix\Iblock\PropertyTable;
use \Bitrix\Iblock\PropertyEnumerationTable;
use YLab\Validation\ComponentValidation;
use YLab\Validation\ValidatorHelper;

/**
 * Class ValidationUserFormComponent
 * Компонент "Добавление пользователя с валидацией"
 *
 * @author Alexander Shatalov
 * @see https://github.com/avshatalov48/bitrix-webinar/
 *
 * @package YLab\Validation\Components
 */
class ValidationUserFormComponent extends ComponentValidation
{
    /**
     * @var array $arIblock массив ИБ
     */
    public $arIblock = [
        "SITE_ID" => ["s1"],
        "CODE" => "users",
        "IBLOCK_TYPE_ID" => "service",
    ];

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
        /**
         * Подключение модуля ИБ
         */
        try {
            Loader::includeModule("iblock");
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function executeComponent()
    {
        $this->arIblock["ID"] = $this->getIblockIdByCode($this->arIblock["CODE"]);

        /**
         * Получение списка значений для свойства "Город"
         */
        $this->arResult["PROPERTY_LIST"] = PropertyEnumerationTable::getList([
            'filter' => [
                'PROPERTY_ID' => $this->getPropertyIdByCode($this->arIblock["ID"], "TOWN_LIST"),
            ],
        ])->fetchAll();

        /**
         * Валидация города
         */
        $this->oValidator->addExtension("town_exists", function ($attribute, $value, $parameters, $validator) {
            $arValidate = PropertyEnumerationTable::getList([
                "select" => ["ID"],
                "filter" => ["=ID" => $value],
                "limit" => 1
            ])->fetch();

            return $arValidate["ID"] ? true : false;
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
                $oCIBlockElement = new \CIBlockElement;

                $iTownPropertyId = PropertyEnumerationTable::getList([
                    "select" => ["ID"],
                    "filter" => [
                        "ID" => $arRequest["TOWN_LIST"],
                    ],
                ])->fetch()["ID"];

                $arFields = [
                    "IBLOCK_ID" => $this->arIblock["ID"],
                    "NAME" => "{$arRequest["USER_NAME"]}|{$arRequest["DATE_BORN"]}|{$arRequest["PHONE"]}",
                    "PROPERTY_VALUES" => [
                        "USER_NAME" => $arRequest["USER_NAME"],
                        "DATE_BORN" => $arRequest["DATE_BORN"],
                        "PHONE" => $arRequest["PHONE"],
                        "TOWN_LIST" => $iTownPropertyId,
                    ],
                ];

                if ($oCIBlockElement->Add($arFields)) {
                    $this->arResult["SUCCESS"] = true;
                }
            } else {
                $this->arResult["ERRORS"] = ValidatorHelper::errorsToArray($this->oValidator);
            }
        }

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
                "regex:/^\+7\d{10}$/", // "regex:/^\+7921\d{7}$/" => (формат +79210000000)
            ],
            "TOWN_LIST" => "required|town_exists",
        ];
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
            return IblockTable::getList([
                "filter" => [
                    "CODE" => $sIblockCode,
                ],
            ])->fetch()["ID"];
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return false;
    }

    /**
     * Получение "ID" свойства по символьному коду
     * @param integer $iIblockId ID ИБ
     * @param string $sPropertyCode Символьный код свойства
     * @return mixed
     */
    public function getPropertyIdByCode($iIblockId, $sPropertyCode)
    {
        $arProperties = [];

        try {
            $arProperties = PropertyTable::getList([
                "filter" => [
                    "IBLOCK_ID" => $iIblockId,
                ],
            ])->fetchAll();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        foreach ($arProperties as $arItem) {
            if ($arItem["CODE"] == $sPropertyCode) {
                return $arItem["ID"];
            }
        }

        return false;
    }
}