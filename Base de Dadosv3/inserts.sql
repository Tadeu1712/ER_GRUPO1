--tabela da area
INSERT INTO `area` (`id`, `nome`) VALUES
(1, 'eletrecidade'),
(2, 'construcao civil'),
(3, 'canalizacao'),
(4, 'carpintaria');

--tabela da publicidade
INSERT INTO `publicidade` (`id`, `url`, `name`, `area_id`) VALUES
(4, 'https://facebook.com', 'teste123', 2),
(5, 'http://localhost/ER/php/tabsTemplate.php?chan', 'teste1', 3),
(10, 'https://facebook.com', 'teste568', 1),
(11, 'http://localhost/ER/php/tabsTemplate.php?addBtn', 'canteste', 3);


INSERT INTO `pessoa` (`id`, `username`, `password`, `email`, `avaliacao`, `nome`, `NIF`, `nr_telefone`, `morada`, `tipo`) VALUES
(1, 'pro', 'teste', 'asdad', 0, 'pro', 12312, NULL, 'asdada', 'profissional\r\n'),
(2, 'cli', 'asdad', 'asdasd', 0, 'cli', 3423424, NULL, 'sadada', 'cliente');