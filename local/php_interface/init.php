<?php

define("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);

$fileAutoload = ROOT_DIR . "/vendor/autoload.php";
if (file_exists($fileAutoload)) {
    require_once($fileAutoload);
}