CREATE TABLE `messages`
(
    `id`                  INT(11) NOT NULL AUTO_INCREMENT,
    `sender_id`           BIGINT(20) NOT NULL,
    `sender_message_id`   BIGINT(20) NOT NULL,
    `receiver_id`         BIGINT(20) NOT NULL,
    `receiver_message_id` BIGINT(20) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;