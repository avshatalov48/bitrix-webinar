<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
?>

<div class="container">
    <form action="" method="post">

        <?= bitrix_sessid_post() ?>
        <? if (count($arResult["ERRORS"])): ?>
            <div class="alert alert-danger" role="alert">
                <?= implode("<hr>", $arResult["ERRORS"]) ?>
            </div>
        <? elseif ($arResult["SUCCESS"]): ?>
            <div class="alert alert-success" role="alert">
                Успешная валидация. Пользователь добавлен.
            </div>
        <? endif; ?>

        <div class="form-group">
            <label for="userName">Имя пользователя</label>
            <input type="text" name="USER_NAME" class="form-control" id="userName" placeholder="Имя"
                   value="<?= $arResult["REQUEST"]["USER_NAME"] ?>">
        </div>

        <div class="form-group">
            <label for="dateBorn">Дата рождения</label>
            <input type="text" name="DATE_BORN" class="form-control" id="dateBorn" placeholder="ДД.ММ.ГГГГ"
                   value="<?= $arResult["REQUEST"]["DATE_BORN"] ?>">
        </div>

        <div class="form-group">
            <label for="phone">Телефон</label>
            <input type="text" name="PHONE" class="form-control" id="phone" placeholder="+79210000000"
                   value="<?= $arResult["REQUEST"]["PHONE"] ?>">
        </div>

        <div class="form-group">
            <label for="townList">Город</label>
            <select class="form-control" id="townList" name="TOWN_LIST">
                <option value="">Выбрать</option>
                <option value="1">Москва</option>
                <option value="2">Санкт-Петербург</option>
                <option value="3">Казань</option>
            </select>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Добавить пользователя</button>
        <button type="reset" class="btn btn-secondary">Сброс</button>
    </form>
</div>