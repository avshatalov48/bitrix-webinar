/*
* install.sql - запросы, которые выполняются при установке модуля
*/

CREATE TABLE `b_ylab_towns` (
  `ID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `TOWN` varchar(255) COLLATE 'utf8_general_ci' NOT NULL,
  `REGION` int(11) NOT NULL
);

/*
* Список городов с регионами
*/
INSERT INTO `b_ylab_towns` (`ID`, `TOWN`, `REGION`) VALUES (NULL, 'Москва', 77);
INSERT INTO `b_ylab_towns` (`ID`, `TOWN`, `REGION`) VALUES (NULL, 'Санкт-Петербург', 78);
INSERT INTO `b_ylab_towns` (`ID`, `TOWN`, `REGION`) VALUES (NULL, 'Тольятти', 63);
INSERT INTO `b_ylab_towns` (`ID`, `TOWN`, `REGION`) VALUES (NULL, 'Липецк', 48);
INSERT INTO `b_ylab_towns` (`ID`, `TOWN`, `REGION`) VALUES (NULL, 'Казань', 16);

CREATE TABLE `b_ylab_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `USER_NAME` varchar(255) COLLATE 'utf8_general_ci' NOT NULL,
  `TOWN_ID` int(11) NOT NULL,
  `DATE_BORN` date NOT NULL,
  `PHONE` varchar(12) COLLATE 'utf8_general_ci' NOT NULL,
  FOREIGN KEY (`TOWN_ID`) REFERENCES `b_ylab_towns` (`ID`)
);

/*
* Данные пользователей для тестов
*/
INSERT INTO `b_ylab_users` (`ID`, `USER_NAME`, `TOWN_ID`, `DATE_BORN`, `PHONE`) VALUES (NULL, 'Вася', '1', '1981-01-01', '+79000000001');
INSERT INTO `b_ylab_users` (`ID`, `USER_NAME`, `TOWN_ID`, `DATE_BORN`, `PHONE`) VALUES (NULL, 'Петя', '2', '1982-02-02', '+79000000002');
INSERT INTO `b_ylab_users` (`ID`, `USER_NAME`, `TOWN_ID`, `DATE_BORN`, `PHONE`) VALUES (NULL, 'Коля', '3', '1983-03-03', '+79000000003');
INSERT INTO `b_ylab_users` (`ID`, `USER_NAME`, `TOWN_ID`, `DATE_BORN`, `PHONE`) VALUES (NULL, 'Ваня', '4', '1984-04-04', '+79000000004');
INSERT INTO `b_ylab_users` (`ID`, `USER_NAME`, `TOWN_ID`, `DATE_BORN`, `PHONE`) VALUES (NULL, 'Кирюша', '5', '1985-05-05', '+79000000005');