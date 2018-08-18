<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

CJSCore::Init(array("fx"));

use Bitrix\Main\Page\Asset;

$mainPage = ($APPLICATION->GetCurPage(false) == '/' || $APPLICATION->GetCurPage(false) == '/en/') ? true : false;
$currPage = $APPLICATION->GetCurPage(false);

$templatePath = SITE_TEMPLATE_PATH;

?>
    <!DOCTYPE html>
    <html lang="ru">

    <head>
        <? $APPLICATION->ShowHead(); ?>
        <title><? $APPLICATION->ShowTitle(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
        <!-- saved from url=(0014)about:internet -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="HandheldFriendly" content="True"/>
        <meta name="format-detection" content="telephone=no"/>
        <meta name="format-detection" content="address=no"/>
        <meta name="x-rim-auto-match" content="none"/>
        <meta name="theme-color" content="#000">
        <meta name="msapplication-navbutton-color" content="#000">
        <meta name="apple-mobile-web-app-status-bar-style" content="#000">

        <? //<OG Meta> ?>
        <meta property="og:title" content="<? $APPLICATION->ShowTitle() ?>"/>
        <meta property="og:type" content="website"/>
        <meta property="og:url" content="<?= $_SERVER['HTTP_HOST'] ?>"/>
        <!--    <meta property="og:image" content="--><? //= SITE_TEMPLATE_PATH ?><!--/img/logo.png"/>-->
        <meta property="og:description" content="<?= $APPLICATION->GetProperty("Description") ?>"/>

        <?
        //<Icons>
        //    Asset::getInstance()->addString('<link rel="shortcut icon" href=' . SITE_TEMPLATE_PATH . '/img/favicon/favicon.ico ">');
        //    Asset::getInstance()->addString('<link rel="apple-touch-icon" href=' . SITE_TEMPLATE_PATH . '/img/favicon/apple-touch-icon.png">');
        //    Asset::getInstance()->addString('<link rel="apple-touch-icon" sizes="72x72" href=' . SITE_TEMPLATE_PATH . '/img/favicon/apple-touch-icon-72x72.png">');
        //    Asset::getInstance()->addString('<link rel="apple-touch-icon" sizes="114x114" href=' . SITE_TEMPLATE_PATH . '/img/favicon/apple-touch-icon-114x114.png">');

        //<CSS>
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/bootstrap.min.css");
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/jquery-ui.min.css");
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/jquery-ui.structure.min.css");
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/jquery-ui.theme.min.css");

        //<JS>
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/jquery-3.3.1.min.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/bootstrap.min.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/jquery-ui.min.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/datepicker-ru.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/jquery.mask.min.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/scripts.js");
        ?>
    </head>

<body>

<noscript>
    <div class="noscript">К сожалению, Ваш браузер не поддерживает скрипты.</div>
</noscript>

<script src="https://yastatic.net/browser-updater/v1/script.js" charset="utf-8"></script>

<script>
    var yaBrowserUpdater = new ya.browserUpdater.init({
        "lang": "ru",
        "browsers": {
            "yabrowser": "15.12",
            "chrome": "54",
            "ie": "10",
            "opera": "41",
            "safari": "8",
            "fx": "49",
            "iron": "35",
            "flock": "Infinity",
            "palemoon": "25",
            "camino": "Infinity",
            "maxthon": "4.5",
            "seamonkey": "2.3"
        },
        "theme": "yellow"
    });
</script>

<div id="panel">
    <? $APPLICATION->ShowPanel(); ?>
</div>