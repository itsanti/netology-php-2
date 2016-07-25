-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `category` (`id`, `name`, `slug`) VALUES
(1,	'php awesome',	'php'),
(2,	'html',	'html'),
(3,	'css styles',	'css'),
(4,	'рецепты',	'rec');

CREATE TABLE `question` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) unsigned DEFAULT NULL,
  `postdate` int(11) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `author_name` varchar(50) NOT NULL,
  `author_email` varchar(100) NOT NULL,
  `q` text NOT NULL,
  `a` text,
  `bot_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `question` (`id`, `cat_id`, `postdate`, `status`, `author_name`, `author_email`, `q`, `a`, `bot_id`) VALUES
(1,	1,	1468432251,	1,	'петя',	'td@fa.ty',	'Хочу в PHP реализовать \"dependency-tracking\" и \"change-notification\" между свойствами разных объектов.',	'Есть мнение, что не стоит городить огород с каскадным обновлением объектов. Скорее всего достаточно будет в целевых объектах иметь свойства-геттеры, которые выполняют некую функцию при каждом запросе к ним. Такой подход будет гораздо проще поддерживать, нежели трекинг.',	NULL),
(2,	1,	1468432634,	1,	'вася',	'sdsd@sd.dd',	'В пхп каждый раз будет выдавать 0. В ноде каждый раз будет прибавлять единицу. То есть в случае с нодой - это такой запущенный один раз процесс, который будет работать, пока его принудительно не остановить, или не произойдет ошибка.',	'Все работают одинаково. Скрипт на PHP может быть запущен как демон и работать пока его не остановить. Равно как и в Java, C#, Ruby, Python, Perl можно сделать так, чтобы скрипт каждый раз умирал.',	NULL),
(3,	3,	1468432745,	1,	'маша',	'sds@xxcxc.cx',	'Привет!\r\nЕсть такая проблема. Есть список, очень большой. Т.к. слишком много элементов браузер начинает лагать, если на компе еще норм, то на ipad вообще полные тормоза. ',	'display: none; лишь уберет элементы с вывода, но в коде они останутся.\r\nСамый лучший вариант - загружать по частям(постранично, infinite scroll)',	NULL),
(4,	2,	1468442772,	1,	'sdsdsdsd',	'sd@sd.sd',	'подскажите',	'подсказал',	NULL),
(16,	4,	1468774272,	1,	'вася',	'sd@sd.sd',	'как сварить суп?',	'просто',	NULL),
(18,	4,	1469131918,	3,	'sdsd',	'sd@sd.sd',	'смотрите на vk.com/awesome',	NULL,	NULL),
(25,	2,	1469397159,	0,	'Tes<b>asd</b>',	'test@example.com',	'Test',	NULL,	NULL),
(26,	1,	1469463919,	0,	'Александр',	'sd@sd.sd',	'как сложить два массива?',	NULL,	NULL),
(28,	NULL,	1469467497,	2,	'Aleksandr Kurov',	'telegram@mysite.dev',	'привет, ты бот?',	'да',	13);

CREATE TABLE `stopword` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `word` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `stopword` (`id`, `word`) VALUES
(10,	'vk\\.com'),
(11,	'покупай');

CREATE TABLE `telegram` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `chat_id` int(11) unsigned NOT NULL,
  `msg_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user` (`id`, `login`, `password`, `status`, `created_at`) VALUES
(1,	'admin',	'$2y$10$jWDQipNQBh.uKgO7tm/LQOvOU6Iz5HhlLR2rp6Dy/Fz/NaEgksHMG',	1,	1468511622),
(7,	'bill',	'$2y$10$lwK2Znyezy9zoh.s46BDY.H9XZn4LOW6rGYedVGdznlOYFNdLLvNi',	1,	1469465090);

-- 2016-07-25 17:35:25
