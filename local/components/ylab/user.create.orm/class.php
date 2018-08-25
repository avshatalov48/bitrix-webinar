<?php

namespace YLab\Validation\Components;

use Bitrix\Main\Loader;
use Bitrix\Main\Type\Date;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Localization\Loc;

use YLab\Validation\ComponentValidation;
use YLab\Validation\ValidatorHelper;

use YLab\Users\Helper;
use YLab\Users\YlabTownsTable;
use YLab\Users\YlabUsersTable;

/**
 * Class ValidationUserFormOrmComponent
 * Добавление пользователя с валидацией ORM
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
     * @access public
     */
    public function onPrepareComponentParams($arParams)
    {
        if (!Loader::includeModule("ylab.users")) {
            echo "<pre>";
            print_r(Loc::getMessage("YLAB_USERS_MODULE_EXISTS"));
            echo "</pre>";
            exit;
        }
        if (!Loader::includeModule("ylab.validation")) {
            echo "<pre>";
            print_r(Loc::getMessage("YLAB_VALIDATION_MODULE_EXISTS"));
            echo "</pre>";
            exit;
        }
        return parent::onPrepareComponentParams($arParams);
    }

    /**
     * ValidationUserFormOrmComponent constructor.
     * @param \CBitrixComponent|null $component
     * @param string $sFile
     * @throws \Bitrix\Main\IO\InvalidPathException
     * @throws \Bitrix\Main\SystemException
     * @access public
     */
    public function __construct(\CBitrixComponent $component = null, $sFile = __FILE__)
    {
        parent::__construct($component, $sFile);
    }

    /**
     * @return mixed|void
     * @throws \Exception
     * @access public
     */
    public function executeComponent()
    {
        $this->arResult["FIELDS"] = $this->getUserFields();
        $this->arResult["TOWN_LIST"] = $this->getTownsList();

        /**
         * Валидация для поля "Город"
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
         * Валидация и действия при успехе и ошибке
         */
        if ($this->oRequest->isPost() && check_bitrix_sessid()) {
            $this->oValidator->setData($this->oRequest->toArray());

            $arRequest = HttpApplication::getInstance()->getContext()->getRequest();

            /**
             * Подстановка значений $_POST в форму, чтобы не сбрасывались при перезагрузке
             */
            foreach ($arRequest as $sKey => $sValue) {
                $this->arResult["REQUEST"][$sKey] = $arRequest[$sKey];
            }

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
     * Правила валидации формы
     *
     * @return array
     * @access protected
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
     * @return array $arTowns
     * @access public
     */
    public function getTownsList()
    {
        $arTowns = [];
        try {
            $arTowns = YlabTownsTable::getList([
                "order" => ["NAME" => "ASC"]
            ])->fetchAll();
        } catch (\Exception $e) {
            Helper::parse($e->getMessage());
        }
        return $arTowns;
    }

    /**
     * Получение списка полей
     *
     * @return array $arUsers
     * @access public
     */
    public function getUserFields()
    {
        $arUsers = [];
        try {
            $arUsers = YlabUsersTable::getList([
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
     * Добавление пользователя в БД
     *
     * @return bool
     * @throws \Bitrix\Main\ObjectException
     * @access public
     */
    public function saveUser()
    {
        $oRes = false;
        $oDate = new Date($this->arResult["REQUEST"]["DATE_BORN"]);

        try {
            $oRes = YlabUsersTable::add([
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