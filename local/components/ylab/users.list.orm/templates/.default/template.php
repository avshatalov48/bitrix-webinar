<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \YLab\Users\Helper;
/** @var array $arResult */
?>

<div class="container">
    <? if (count($arResult)): ?>
        <h3>users.list.orm</h3>
        <table class="table table-striped">
            <? foreach ($arResult as $iOrder => $arItem): ?>
                <? //<Шапка таблицы> ?>
                <? if ($iOrder == 0): ?>
                    <thead>
                    <tr>
                        <? foreach ($arItem as $sKey => $sValue): ?>
                            <th>
                                <?= Helper::i18n($sKey) ?>
                            </th>
                        <? endforeach; ?>
                    </tr>
                    </thead>
                    <tbody>
                <? endif; ?>
                <? //</Шапка таблицы> ?>

                <? //<Тело таблицы> ?>
                <tr>
                    <? foreach ($arItem as $sKey => $sValue): ?>
                        <td>
                            <?= $sValue ?>
                        </td>
                    <? endforeach; ?>
                </tr>
                <? //</Тело таблицы> ?>
            <? endforeach; ?>
            </tbody>
        </table>
    <? else: ?>
        <div class="alert alert-danger" role="alert">
            <?= Helper::i18n("ERROR_USER_LIST_IS_EMPTY") ?>
        </div>
    <? endif; ?>
</div>