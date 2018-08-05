<?
/**
 * @global \CMain $APPLICATION
 */
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("1С-Битрикс обучение");
?>

<? $APPLICATION->IncludeComponent("ylab:users.list", "", ["IBLOCK_ID" => 1, "ACTIVE" => "Y"]); ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>