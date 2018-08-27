<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \YLab\Users\Helper;

/** @var array $arResult */
?>

<div class="container">
    <? if ($arResult["FIELDS"]): ?>
        <h3>users.create.orm</h3>
        <form action="" method="post">

            <?= bitrix_sessid_post() ?>

            <? //<Сообщения о статусе валидации> ?>
            <? if (count($arResult["ERRORS"])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= implode("<hr>", $arResult["ERRORS"]) ?>
                </div>
            <? elseif ($arResult["SUCCESS"]): ?>
                <div class="alert alert-success" role="alert">
                    <?= Helper::i18n("SUCCESS") ?>
                </div>
            <? endif; ?>
            <? //</Сообщения о статусе валидации> ?>

            <? //<Поля> ?>
            <? foreach ($arResult["FIELDS"] as $sKey => $sValue): ?>
                <div class="form-group">
                    <label for="<?= $sKey ?>"><?= Helper::i18n($sKey) ?></label>
                    <input type="text" name="<?= $sKey ?>"
                           class="form-control" id="<?= $sKey ?>"
                           placeholder="<?= Helper::i18n("PLACEHOLDER_" . $sKey) ?>"
                           value="<?= $arResult["REQUEST"][$sKey] ?>">
                </div>
            <? endforeach; ?>
            <? //</Поля> ?>

            <? //<Список городов> ?>
            <div class="form-group">
                <label for="TOWN_LIST"><?= Helper::i18n("PLACEHOLDER_TOWN_LIST") ?></label>
                <select class="form-control" id="TOWN_LIST" name="TOWN_LIST">
                    <option value="">
                        <?= Helper::i18n("SELECT_DEFAULT") ?>
                    </option>
                    <? foreach ($arResult["TOWN_LIST"] as $arItem): ?>
                        <option
                            <? if ($arResult["REQUEST"]["TOWN_LIST"] == $arItem["ID"]): ?>
                                selected
                            <? endif; ?>
                                value="<?= $arItem["ID"] ?>"><?= $arItem["NAME"] ?>
                        </option>
                    <? endforeach; ?>
                </select>
            </div>
            <? //</Список городов> ?>

            <? //<Кнопки> ?>
            <button type="submit" name="submit" class="btn btn-primary">
                <?= Helper::i18n("BUTTON_SUBMIT") ?>
            </button>
            <button type="reset" class="btn btn-secondary" id="form__button-reset">
                <?= Helper::i18n("BUTTON_RESET") ?>
            </button>
            <? //</Кнопки> ?>
        </form>
    <? else: ?>
        <div class="alert alert-danger" role="alert">
            <?= Helper::i18n("ERROR_DB_NOT_EXIST") ?>
        </div>
    <? endif; ?>
</div>