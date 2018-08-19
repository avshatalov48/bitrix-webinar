<?
/**
 * @global \CMain $APPLICATION
 */
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("1С-Битрикс обучение");
?>

<?//
//
//try {
//    \Bitrix\Main\Loader::includeModule("ylab.webinar");
//} catch (\Exception $e) {
//    echo $e->getMessage();
//}
//
//echo \YLab\Webinar\Helper::test();
//
//?>

<? $APPLICATION->IncludeComponent("ylab:user.create", "", []); ?>

<? $APPLICATION->IncludeComponent("ylab:users.list", "", ["CODE" => "users", "ACTIVE" => "Y"]); ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>