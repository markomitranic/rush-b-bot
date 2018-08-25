<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180418132830 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_survey (id INT AUTO_INCREMENT NOT NULL, user_id BIGINT DEFAULT NULL, age INT DEFAULT NULL, twitter_handle VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, first_time TINYINT(1) DEFAULT NULL, occupation VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_C80D80C1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_survey ADD CONSTRAINT FK_C80D80C1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD survey_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B3FE509D FOREIGN KEY (survey_id) REFERENCES user_survey (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649B3FE509D ON user (survey_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B3FE509D');
        $this->addSql('DROP TABLE user_survey');
        $this->addSql('DROP INDEX UNIQ_8D93D649B3FE509D ON user');
        $this->addSql('ALTER TABLE user DROP survey_id');
    }
}
