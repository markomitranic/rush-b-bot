<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180414125113 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE speaker (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, company VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lecture ADD speaker_id INT DEFAULT NULL, DROP name, DROP meta_name');
        $this->addSql('ALTER TABLE lecture ADD CONSTRAINT FK_C1677948D04A0F27 FOREIGN KEY (speaker_id) REFERENCES speaker (id)');
        $this->addSql('CREATE INDEX IDX_C1677948D04A0F27 ON lecture (speaker_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lecture DROP FOREIGN KEY FK_C1677948D04A0F27');
        $this->addSql('DROP TABLE speaker');
        $this->addSql('DROP INDEX IDX_C1677948D04A0F27 ON lecture');
        $this->addSql('ALTER TABLE lecture ADD name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD meta_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP speaker_id');
    }
}
