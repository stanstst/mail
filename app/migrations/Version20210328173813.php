<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210328173813 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Creates emails table';
    }

    public function up(Schema $schema) : void
    {
        $sql = <<<SQL
                CREATE TABLE `emails` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `from_email` varchar(255) NOT NULL,
                    `from_name` varchar(255) NOT NULL,
                    `subject` varchar(255) NOT NULL,
                    `recipients` varchar(255) NOT NULL,
                    `text_part` LONGTEXT DEFAULT NULL,
                    `html_part` LONGTEXT DEFAULT NULL,
                    `status` varchar(255) NOT NULL,
                    `created_at` datetime DEFAULT NULL,
                    `updated_at` datetime DEFAULT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL;
        $this->addSql($sql);
    }

    public function down(Schema $schema) : void
    {
        $sql = <<<SQL
                DROP TABLE `emails`;
SQL;
        $this->addSql($sql);
    }
}
