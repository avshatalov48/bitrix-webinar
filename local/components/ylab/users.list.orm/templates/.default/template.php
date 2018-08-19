<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

/** @var array $arResult */
?>

<div class="container">
    <? if ($arResult): ?>
        <table class="table table-striped">
            <? foreach ($arResult as $iOrder => $arItem): ?>
                <? //<Шапка таблицы> ?>
                <? if ($iOrder == 0): ?>
                    <thead>
                    <tr>
                        <? foreach ($arItem["PROPERTIES"] as $arProperty): ?>
                            <th>
                                <?= $arProperty["NAME"] ?>
                            </th>
                        <? endforeach; ?>
                    </tr>
                    </thead>
                    <tbody>
                <? endif; ?>
                <? //</Шапка таблицы> ?>

                <? //<Тело таблицы> ?>
                <tr>
                    <? foreach ($arItem["PROPERTIES"] as $arProperty): ?>
                        <td>
                            <?= $arProperty["VALUE"] ?>
                        </td>
                    <? endforeach; ?>
                </tr>
                <? //</Тело таблицы> ?>
            <? endforeach; ?>
            </tbody>
        </table>
    <? else: ?>
        <div class="alert alert-danger" role="alert">
            <?= Loc::getMessage("ERROR_USER_LIST_IS_EMPTY") ?>
        </div>
    <? endif; ?>
</div>