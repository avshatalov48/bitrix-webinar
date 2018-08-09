<?php

use Phinx\Migration\AbstractMigration;

/**
 * Class Init
 * Стартовая миграция с настройками
 */
class Init extends AbstractMigration
{
    /**
     * Накатываем миграцию - "phinx migrate"
     * @throws Exception
     */
    public function up()
    {
        try {
            $oConnection = \Bitrix\Main\Application::getConnection();
            $oConnection->query("CREATE TABLE `b_ylab_test` (`ID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY);");
        } catch (\Exception $e) {
            throw new Exception("Error CREATE table");
        }
    }

    /**
     * Откатываем миграцию - "phinx rollback"
     * @throws Exception
     */
    public function down()
    {
        try {
            $oConnection = \Bitrix\Main\Application::getConnection();
            $oConnection->query("DROP TABLE `b_ylab_test`;");
        } catch (\Exception $e) {
            throw new Exception("Error DROP table");
        }
    }

}
