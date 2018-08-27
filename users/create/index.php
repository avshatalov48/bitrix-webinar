<?php

/**
 * @global \CMain $APPLICATION
 */
global $APPLICATION;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Добавление пользователей");
?>

<? $APPLICATION->IncludeComponent("ylab:user.create", "", []); ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>