CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `firstname` varchar(255) CHARACTER SET utf8 NOT NULL,
  `lastname` varchar(255) CHARACTER SET utf8 NOT NULL,
  `birthday` DATE NOT NULL,
  `password` varchar(255) NOT NULL,
  `favorite` JSON,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT INTO `user` (username, email, firstname, lastname, birthday, password) 
VALUES ('JohnDoe', 'john.doe@poke.mon', 'John', 'Doe', '1987-01-01', '$2y$10$vtV6zZ/Aw8vhlEXM57I3/OOh8E4G.Y5Wzz7aBuBeV32J9da7IGgsa');

-- password = pokemon