CREATE TABLE `users` (
    `userId` int(3) NOT NULL,
    `password` varchar(255) NOT NULL,
    PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`userId`, `password`) VALUES
(:userId, :password);