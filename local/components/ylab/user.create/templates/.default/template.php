<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

/** @var array $arResult */
?>

<div class="container">
    <? if (count($arResult["PROPERTIES"])): ?>
        <h3>users.create.ib</h3>
        <form action="" method="post">

            <?= bitrix_sessid_post() ?>

            <? //<Сообщения о статусе валидации> ?>
            <? if (count($arResult["ERRORS"])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= implode("<hr>", $arResult["ERRORS"]) ?>
                </div>
            <? elseif ($arResult["SUCCESS"]): ?>
                <div class="alert alert-success" role="alert">
                    <?= Loc::getMessage("SUCCESS") ?>
                </div>
            <? endif; ?>
            <? //</Сообщения о статусе валидации> ?>

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
                            <option value="">
                                <?= Loc::getMessage("SELECT_DEFAULT") ?>
                            </option>
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

            <button type="submit" name="submit" class="btn btn-primary">
                <?= Loc::getMessage("BUTTON_SUBMIT") ?>
            </button>
            <button type="reset" class="btn btn-secondary">
                <?= Loc::getMessage("BUTTON_RESET") ?>
            </button>
        </form>
    <? else: ?>
        <div class="alert alert-danger" role="alert">
            <?= Loc::getMessage("ERROR_IB_NOT_EXIST") ?>
        </div>
    <? endif; ?>
</div>