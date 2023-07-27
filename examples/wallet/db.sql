CREATE TABLE `users`
(
    `id`      BIGINT(20)     NOT NULL,
    `step`    VARCHAR(64)    NOT NULL DEFAULT 'main',
    `value`   TEXT           NULL     DEFAULT NULL,
    `balance` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    PRIMARY KEY (`id`)
);

CREATE TABLE `orders`
(
    `id`          INT          NOT NULL AUTO_INCREMENT,
    `user_id`     BIGINT(20)   NOT NULL,
    `type`        VARCHAR(30)  NOT NULL,
    `amount`      DOUBLE(10, 2) NOT NULL,
    `description` TEXT         NOT NULL,
    `extra_info`  longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `history`
(
    `id`     INT                         NOT NULL AUTO_INCREMENT,
    `type`   ENUM ('deposit','transfer') NOT NULL,
    `amount` DECIMAL(10, 2)              NOT NULL,
    `date`   INT                         NOT NULL,
    `user_id` bigint(20) NOT NULL,
    `target_id` bigint(20) NOT NULL DEFAULT 0,
    `order_id` int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
);