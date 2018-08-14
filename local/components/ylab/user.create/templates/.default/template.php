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

<div class="container" style="margin-top: 100px;">
    <? if ($arResult["PROPERTIES"]): ?>
        <form action="" method="post">

            <?= bitrix_sessid_post() ?>

            <? if (count($arResult["ERRORS"])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= implode("<hr>", $arResult["ERRORS"]) ?>
                </div>
            <? elseif ($arResult["SUCCESS"]["MESSAGE"]): ?>
                <div class="alert alert-success" role="alert">
                    <?= $arResult["SUCCESS"]["MESSAGE"] ?>
                </div>
            <? endif; ?>

            <? foreach ($arResult["PROPERTIES"] as $arProperty): ?>
                <div class="form-group">
                    <label for="<?= $arProperty["CODE"] ?>"><?= $arProperty["NAME"] ?></label>
                    <? //<Строка> ?>
                    <? if ($arProperty["PROPERTY_TYPE"] == "S"): ?>
                        <input type="text" name="<?= $arProperty["CODE"] ?>"
                               class="form-control" id="<?= $arProperty["CODE"] ?>"
                               placeholder="<?= $arProperty["PLACEHOLDER"] ?>"
                               value="<?= $arResult["REQUEST"][$arProperty["CODE"]] ?>">
                    <? endif; ?>
                    <? //</Строка> ?>
                    <? //<Список> ?>
                    <? if ($arProperty["PROPERTY_TYPE"] == "L"): ?>
                        <select class="form-control" id="<?= $arProperty["CODE"] ?>"
                                name="<?= $arProperty["CODE"] ?>">
                            <option value="">Выбрать</option>
                            <? foreach ($arResult["TOWN_LIST"] as $arItem): ?>
                                <option
                                    <? if ($arResult["REQUEST"]["TOWN_LIST"] == $arItem["XML_ID"]): ?>
                                        selected
                                    <? endif; ?>
                                        value="<?= $arItem["XML_ID"] ?>"><?= $arItem["VALUE"] ?>
                                </option>
                            <? endforeach; ?>
                        </select>
                    <? endif; ?>
                    <? //</Список> ?>
                </div>
            <? endforeach; ?>

            <button type="submit" name="submit" class="btn btn-primary">Добавить пользователя</button>
            <button type="reset" class="btn btn-secondary">Сброс</button>
        </form>
    <? else: ?>
        <div class="alert alert-danger" role="alert">
            Проверьте существование инфоблока "Пользователи". Выполните миграции.
        </div>
    <? endif; ?>
</div>