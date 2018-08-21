<?
/**
 * @global \CMain $APPLICATION
 */
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("1С-Битрикс обучение");
?>

<?// $APPLICATION->IncludeComponent("ylab:user.create", "", []); ?>

<? $APPLICATION->IncludeComponent("ylab:user.create.orm", "", []); ?>

<? // $APPLICATION->IncludeComponent("ylab:users.list", "", ["CODE" => "users", "ACTIVE" => "Y"]); ?>

<? $APPLICATION->IncludeComponent("ylab:users.list.orm", "", []); ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>