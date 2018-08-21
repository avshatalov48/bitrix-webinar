<?php

namespace YLab\Validation\Components;

use \Bitrix\Main\Loader;
use \Bitrix\Main\HttpApplication;
use \Bitrix\Iblock\IblockTable;
use \Bitrix\Iblock\PropertyTable;
use \Bitrix\Iblock\PropertyEnumerationTable;
use YLab\Users\CountriesTable;
use YLab\Users\TownsTable;
use YLab\Validation\ComponentValidation;
use YLab\Validation\ValidatorHelper;

use \YLab\Users\UsersTable;
use \Bitrix\Main\Localization\Loc;
use \YLab\Users\Helper;

use Bitrix\Main\Type\Date;

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
     * @param $arParams
     * @return array
     * @throws \Bitrix\Main\LoaderException
     */
    public function onPrepareComponentParams($arParams)
    {
        if (!Loader::includeModule("ylab.users")) {
            echo "<pre>" . Loc::getMessage("YLAB_USERS_MODULE_EXISTS") . "</pre>";
        }
        if (!Loader::includeModule("ylab.validation")) {
            echo "<pre>" . Loc::getMessage("YLAB_VALIDATION_MODULE_EXISTS") . "</pre>";
        }
        return parent::onPrepareComponentParams($arParams);
    }

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
        $this->arResult["FIELDS"] = $this->getUsersFields();
        $this->arResult["TOWN_LIST"] = $this->getTownsList();

        /**
         * Задание значений placeholder для полей
         */
        foreach ($this->arResult["FIELDS"] as $sKey => $sValue) {
            $this->arResult["PLACEHOLDERS"][$sKey] = Helper::i18n("PLACEHOLDERS_" . $sKey);
        }

        /**
         * Валидация города
         */
        $this->oValidator->addExtension("town_exists", function ($attribute, $value, $parameters, $validator) {
            foreach ($this->arResult["TOWN_LIST"] as $arItem) {
                if ($arItem["ID"] == $value) {
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
                 * Запись пользователя в БД при успешной валидации
                 */
                if ($this->saveUser()) {
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
            $arTowns = TownsTable::getList([
                "order" => ["NAME" => "ASC"]
            ])->fetchAll();
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
    protected function getUsersFields()
    {
        $arUsers = [];
        try {
            $arUsers = UsersTable::getList([
                "select" => [
                    "USER_NAME",
                    "DATE_BORN",
                    "PHONE"
                ],
                "limit" => 1
            ])->fetchAll();
        } catch (\Exception $e) {
            Helper::parse($e->getMessage());
        }
        return $arUsers[0];
    }

    /**
     * @return bool
     * @throws \Bitrix\Main\ObjectException
     */
    protected function saveUser()
    {
        $oRes = false;
        $oDate = new Date($this->arResult["REQUEST"]["DATE_BORN"]);

        try {
            $oRes = UsersTable::add([
                'USER_NAME' => $this->arResult["REQUEST"]["USER_NAME"],
                'DATE_BORN' => $oDate,
                'PHONE' => $this->arResult["REQUEST"]["PHONE"],
                'TOWN_ID' => $this->arResult["REQUEST"]["TOWN_LIST"],
            ])->isSuccess();
        } catch (\Exception $e) {
            Helper::parse($e->getMessage());
        }

        return $oRes;
    }
}