/*
* install.sql - запросы, которые выполняются при установке модуля
*/

/*
* Страны
*/
CREATE TABLE `b_ylab_countries` (
  `ID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `NAME` varchar(255) COLLATE 'utf8_general_ci' NOT NULL
);

/*
* Данные стран для тестов
*/
INSERT INTO `b_ylab_countries` (`ID`, `NAME`) VALUES (NULL, 'Россия');
INSERT INTO `b_ylab_countries` (`ID`, `NAME`) VALUES (NULL, 'Германия');
INSERT INTO `b_ylab_countries` (`ID`, `NAME`) VALUES (NULL, 'Франция');

/*
* Города
*/
CREATE TABLE `b_ylab_towns` (
  `ID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `NAME` varchar(255) COLLATE 'utf8_general_ci' NOT NULL,
  `COUNTRY_ID` int(11) NOT NULL,
  FOREIGN KEY (`COUNTRY_ID`) REFERENCES `b_ylab_countries` (`ID`)
);

/*
* Данные городов для тестов
*/
INSERT INTO `b_ylab_towns` (`ID`, `NAME`, `COUNTRY_ID`) VALUES (NULL, 'Москва', 1);
INSERT INTO `b_ylab_towns` (`ID`, `NAME`, `COUNTRY_ID`) VALUES (NULL, 'Санкт-Петербург', 1);
INSERT INTO `b_ylab_towns` (`ID`, `NAME`, `COUNTRY_ID`) VALUES (NULL, 'Тольятти', 1);
INSERT INTO `b_ylab_towns` (`ID`, `NAME`, `COUNTRY_ID`) VALUES (NULL, 'Липецк', 1);
INSERT INTO `b_ylab_towns` (`ID`, `NAME`, `COUNTRY_ID`) VALUES (NULL, 'Казань', 1);
INSERT INTO `b_ylab_towns` (`ID`, `NAME`, `COUNTRY_ID`) VALUES (NULL, 'Берлин', 2);
INSERT INTO `b_ylab_towns` (`ID`, `NAME`, `COUNTRY_ID`) VALUES (NULL, 'Париж', 3);

/*
* Пользователи
*/
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
INSERT INTO `b_ylab_users` (`ID`, `USER_NAME`, `TOWN_ID`, `DATE_BORN`, `PHONE`) VALUES (NULL, 'Филипп', '6', '1986-06-06', '+79000000006');
INSERT INTO `b_ylab_users` (`ID`, `USER_NAME`, `TOWN_ID`, `DATE_BORN`, `PHONE`) VALUES (NULL, 'Пьер', '7', '1987-07-07', '+79000000007');