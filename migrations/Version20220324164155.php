<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220324164155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_admin (project_id INT NOT NULL, admin_id INT NOT NULL, INDEX IDX_9B5B04E8166D1F9C (project_id), INDEX IDX_9B5B04E8642B8210 (admin_id), PRIMARY KEY(project_id, admin_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_admin ADD CONSTRAINT FK_9B5B04E8166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_admin ADD CONSTRAINT FK_9B5B04E8642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project CHANGE nicoka_id nicoka_id VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE project_admin');
        $this->addSql('ALTER TABLE project CHANGE nicoka_id nicoka_id INT DEFAULT NULL');
    }
}
