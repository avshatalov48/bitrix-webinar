<?php

/**
 * @global \CMain $APPLICATION
 */
global $APPLICATION;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Вывод списка пользователей");
?>

<? $APPLICATION->IncludeComponent("ylab:users.list", "", ["CODE" => "users", "ACTIVE" => "Y"]); ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>