<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200713133907 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `character` (id INT AUTO_INCREMENT NOT NULL, short_name VARCHAR(255) DEFAULT NULL, full_name VARCHAR(255) DEFAULT NULL, alternates VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, role INT DEFAULT NULL, bio LONGTEXT DEFAULT NULL, notes LONGTEXT DEFAULT NULL, goals LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE character_scene (character_id INT NOT NULL, scene_id INT NOT NULL, INDEX IDX_27BA08B81136BE75 (character_id), INDEX IDX_27BA08B8166053B4 (scene_id), PRIMARY KEY(character_id, scene_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE character_scene ADD CONSTRAINT FK_27BA08B81136BE75 FOREIGN KEY (character_id) REFERENCES `character` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE character_scene ADD CONSTRAINT FK_27BA08B8166053B4 FOREIGN KEY (scene_id) REFERENCES scene (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE scene ADD viewpoint_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE scene ADD CONSTRAINT FK_D979EFDA9D3D3A25 FOREIGN KEY (viewpoint_id) REFERENCES `character` (id)');
        $this->addSql('CREATE INDEX IDX_D979EFDA9D3D3A25 ON scene (viewpoint_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scene DROP FOREIGN KEY FK_D979EFDA9D3D3A25');
        $this->addSql('ALTER TABLE character_scene DROP FOREIGN KEY FK_27BA08B81136BE75');
        $this->addSql('DROP TABLE `character`');
        $this->addSql('DROP TABLE character_scene');
        $this->addSql('DROP INDEX IDX_D979EFDA9D3D3A25 ON scene');
        $this->addSql('ALTER TABLE scene DROP viewpoint_id');
    }
}
