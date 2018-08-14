<?
/**
 * @global \CMain $APPLICATION
 */
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("1С-Битрикс обучение");
?>

<? $APPLICATION->IncludeComponent("ylab:user.create", "", []); ?>

<?// $APPLICATION->IncludeComponent("ylab:users.list", "", ["CODE" => "users", "ACTIVE" => "Y"]); ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>