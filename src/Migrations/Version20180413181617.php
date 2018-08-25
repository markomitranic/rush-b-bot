<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180413181617 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE botan_shortener (id BIGINT AUTO_INCREMENT NOT NULL, user_id BIGINT DEFAULT NULL, url TEXT NOT NULL COMMENT \'Original URL\', short_url CHAR(255) NOT NULL COMMENT \'Shortened URL\', created_at DATETIME DEFAULT NULL COMMENT \'Entry date creation\', INDEX user_id (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE callback_query (id BIGINT AUTO_INCREMENT NOT NULL, user_id BIGINT DEFAULT NULL, chat_id BIGINT DEFAULT NULL, message_id BIGINT DEFAULT NULL, inline_message_id CHAR(255) DEFAULT NULL COMMENT \'Identifier of the message sent via the bot in inline mode, that originated the query\', data CHAR(255) NOT NULL COMMENT \'Data associated with the callback button\', created_at DATETIME DEFAULT NULL COMMENT \'Entry date creation\', INDEX user_id (user_id), INDEX chat_id (chat_id), INDEX message_id (message_id), INDEX chat_id_2 (chat_id, message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat (id BIGINT AUTO_INCREMENT NOT NULL, type VARCHAR(200) DEFAULT NULL, title CHAR(255) DEFAULT NULL COMMENT \'Chat (group) title, is null if chat type is private\', username CHAR(255) DEFAULT NULL COMMENT \'Username, for private chats, supergroups and channels if available\', all_members_are_administrators TINYINT(1) DEFAULT NULL COMMENT \'True if a all members of this group are admins\', created_at DATETIME DEFAULT NULL COMMENT \'Entry date creation\', updated_at DATETIME DEFAULT NULL COMMENT \'Entry date update\', old_id BIGINT DEFAULT NULL COMMENT \'Unique chat identifier, this is filled when a group is converted to a supergroup\', INDEX old_id (old_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chosen_inline_result (id BIGINT AUTO_INCREMENT NOT NULL, user_id BIGINT DEFAULT NULL, result_id CHAR(255) NOT NULL COMMENT \'Identifier for this result\', location CHAR(255) DEFAULT NULL COMMENT \'Location object, user\'\'s location\', inline_message_id CHAR(255) DEFAULT NULL COMMENT \'Identifier of the sent inline message\', query TEXT NOT NULL COMMENT \'The query that was used to obtain the result\', created_at DATETIME DEFAULT NULL COMMENT \'Entry date creation\', INDEX user_id (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation (id BIGINT AUTO_INCREMENT NOT NULL, user_id BIGINT DEFAULT NULL, chat_id BIGINT DEFAULT NULL, status VARCHAR(200) DEFAULT NULL, command VARCHAR(160) DEFAULT NULL COMMENT \'Default command to execute\', notes TEXT DEFAULT NULL COMMENT \'Data stored from command\', created_at DATETIME DEFAULT NULL COMMENT \'Entry date creation\', updated_at DATETIME DEFAULT NULL COMMENT \'Entry date update\', INDEX user_id (user_id), INDEX chat_id (chat_id), INDEX status (status), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE edited_message (id BIGINT AUTO_INCREMENT NOT NULL, chat_id BIGINT DEFAULT NULL, user_id BIGINT DEFAULT NULL, message_id BIGINT UNSIGNED DEFAULT NULL COMMENT \'Unique message identifier\', edit_date DATETIME DEFAULT NULL COMMENT \'Date the message was edited in timestamp format\', text TEXT DEFAULT NULL COMMENT \'For text messages, the actual UTF-8 text of the message max message length 4096 char utf8\', entities TEXT DEFAULT NULL COMMENT \'For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text\', caption TEXT DEFAULT NULL COMMENT \'For message with caption, the actual UTF-8 text of the caption\', INDEX chat_id (chat_id), INDEX message_id (message_id), INDEX user_id (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inline_query (id BIGINT AUTO_INCREMENT NOT NULL, user_id BIGINT DEFAULT NULL, location CHAR(255) DEFAULT NULL COMMENT \'Location of the user\', query TEXT NOT NULL COMMENT \'Text of the query\', offset CHAR(255) DEFAULT NULL COMMENT \'Offset of the result\', created_at DATETIME DEFAULT NULL COMMENT \'Entry date creation\', INDEX user_id (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id BIGINT NOT NULL, chat_id BIGINT NOT NULL, user_id BIGINT DEFAULT NULL, forward_from_chat BIGINT DEFAULT NULL, reply_to_chat BIGINT DEFAULT NULL, reply_to_message BIGINT DEFAULT NULL, left_chat_member BIGINT DEFAULT NULL, date DATETIME DEFAULT NULL COMMENT \'Date the message was sent in timestamp format\', forward_from BIGINT DEFAULT NULL COMMENT \'Unique user identifier, sender of the original message\', forward_from_message_id BIGINT DEFAULT NULL COMMENT \'Unique chat identifier of the original message in the channel\', forward_date DATETIME DEFAULT NULL COMMENT \'date the original message was sent in timestamp format\', media_group_id TEXT DEFAULT NULL COMMENT \'The unique identifier of a media message group this message belongs to\', text TEXT DEFAULT NULL COMMENT \'For text messages, the actual UTF-8 text of the message max message length 4096 char utf8mb4\', entities TEXT DEFAULT NULL COMMENT \'For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text\', audio TEXT DEFAULT NULL COMMENT \'Audio object. Message is an audio file, information about the file\', document TEXT DEFAULT NULL COMMENT \'Document object. Message is a general file, information about the file\', photo TEXT DEFAULT NULL COMMENT \'Array of PhotoSize objects. Message is a photo, available sizes of the photo\', sticker TEXT DEFAULT NULL COMMENT \'Sticker object. Message is a sticker, information about the sticker\', video TEXT DEFAULT NULL COMMENT \'Video object. Message is a video, information about the video\', voice TEXT DEFAULT NULL COMMENT \'Voice Object. Message is a Voice, information about the Voice\', video_note TEXT DEFAULT NULL COMMENT \'VoiceNote Object. Message is a Video Note, information about the Video Note\', contact TEXT DEFAULT NULL COMMENT \'Contact object. Message is a shared contact, information about the contact\', location TEXT DEFAULT NULL COMMENT \'Location object. Message is a shared location, information about the location\', venue TEXT DEFAULT NULL COMMENT \'Venue object. Message is a Venue, information about the Venue\', caption TEXT DEFAULT NULL COMMENT \'For message with caption, the actual UTF-8 text of the caption\', new_chat_members TEXT DEFAULT NULL COMMENT \'List of unique user identifiers, new member(s) were added to the group, information about them (one of these members may be the bot itself)\', new_chat_title CHAR(255) DEFAULT NULL COMMENT \'A chat title was changed to this value\', new_chat_photo TEXT DEFAULT NULL COMMENT \'Array of PhotoSize objects. A chat photo was change to this value\', delete_chat_photo TINYINT(1) DEFAULT NULL COMMENT \'Informs that the chat photo was deleted\', group_chat_created TINYINT(1) DEFAULT NULL COMMENT \'Informs that the group has been created\', supergroup_chat_created TINYINT(1) DEFAULT NULL COMMENT \'Informs that the supergroup has been created\', channel_chat_created TINYINT(1) DEFAULT NULL COMMENT \'Informs that the channel chat has been created\', migrate_to_chat_id BIGINT DEFAULT NULL COMMENT \'Migrate to chat identifier. The group has been migrated to a supergroup with the specified identifier\', migrate_from_chat_id BIGINT DEFAULT NULL COMMENT \'Migrate from chat identifier. The supergroup has been migrated from a group with the specified identifier\', pinned_message TEXT DEFAULT NULL COMMENT \'Message object. Specified message was pinned\', connected_website TEXT DEFAULT NULL COMMENT \'The domain name of the website on which the user has logged in.\', INDEX user_id (user_id), INDEX forward_from_chat (forward_from_chat), INDEX reply_to_chat (reply_to_chat), INDEX reply_to_message (reply_to_message), INDEX left_chat_member (left_chat_member), INDEX migrate_from_chat_id (migrate_from_chat_id), INDEX migrate_to_chat_id (migrate_to_chat_id), INDEX reply_to_chat_2 (reply_to_chat, reply_to_message), INDEX IDX_B6BD307F1A9A7125 (chat_id), PRIMARY KEY(id, chat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE request_limiter (id BIGINT AUTO_INCREMENT NOT NULL, chat_id CHAR(255) DEFAULT NULL COMMENT \'Unique chat identifier\', inline_message_id CHAR(255) DEFAULT NULL COMMENT \'Identifier of the sent inline message\', method CHAR(255) DEFAULT NULL COMMENT \'Request method\', created_at DATETIME DEFAULT NULL COMMENT \'Entry date creation\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE telegram_update (id BIGINT AUTO_INCREMENT NOT NULL, chat_id BIGINT DEFAULT NULL, message_id BIGINT DEFAULT NULL, inline_query_id BIGINT DEFAULT NULL, chosen_inline_result_id BIGINT DEFAULT NULL, callback_query_id BIGINT DEFAULT NULL, edited_message_id BIGINT DEFAULT NULL, INDEX message_id (chat_id, message_id), INDEX inline_query_id (inline_query_id), INDEX chosen_inline_result_id (chosen_inline_result_id), INDEX callback_query_id (callback_query_id), INDEX edited_message_id (edited_message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id BIGINT AUTO_INCREMENT NOT NULL, is_bot TINYINT(1) DEFAULT NULL COMMENT \'True if this user is a bot\', first_name CHAR(255) NOT NULL COMMENT \'User\'\'s first name\', last_name CHAR(255) DEFAULT NULL COMMENT \'User\'\'s last name\', username CHAR(191) DEFAULT NULL COMMENT \'User\'\'s username\', language_code CHAR(10) DEFAULT NULL COMMENT \'User\'\'s system language\', created_at DATETIME DEFAULT NULL COMMENT \'Entry date creation\', updated_at DATETIME DEFAULT NULL COMMENT \'Entry date update\', INDEX username (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_chat (user_id BIGINT NOT NULL, chat_id BIGINT NOT NULL, INDEX IDX_1F1CBE63A76ED395 (user_id), INDEX IDX_1F1CBE631A9A7125 (chat_id), PRIMARY KEY(user_id, chat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE botan_shortener ADD CONSTRAINT FK_717366FAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE callback_query ADD CONSTRAINT FK_D36CF3A1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE callback_query ADD CONSTRAINT FK_D36CF3A11A9A7125537A1329 FOREIGN KEY (chat_id, message_id) REFERENCES message (chat_id, id)');
        $this->addSql('ALTER TABLE chosen_inline_result ADD CONSTRAINT FK_12A363A2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E91A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE edited_message ADD CONSTRAINT FK_7D194E541A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE edited_message ADD CONSTRAINT FK_7D194E54A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE inline_query ADD CONSTRAINT FK_F43FD7D6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCEE99696 FOREIGN KEY (forward_from_chat) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9FCD8AA732E801DC FOREIGN KEY (reply_to_chat, reply_to_message) REFERENCES message (chat_id, id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F3F638537 FOREIGN KEY (left_chat_member) REFERENCES user (id)');
        $this->addSql('ALTER TABLE telegram_update ADD CONSTRAINT FK_EADEC71A9A7125537A1329 FOREIGN KEY (chat_id, message_id) REFERENCES message (chat_id, id)');
        $this->addSql('ALTER TABLE telegram_update ADD CONSTRAINT FK_EADEC7975AF2A3 FOREIGN KEY (inline_query_id) REFERENCES inline_query (id)');
        $this->addSql('ALTER TABLE telegram_update ADD CONSTRAINT FK_EADEC7BDB2861A FOREIGN KEY (chosen_inline_result_id) REFERENCES chosen_inline_result (id)');
        $this->addSql('ALTER TABLE telegram_update ADD CONSTRAINT FK_EADEC7C56E6DB9 FOREIGN KEY (callback_query_id) REFERENCES callback_query (id)');
        $this->addSql('ALTER TABLE telegram_update ADD CONSTRAINT FK_EADEC7765896A FOREIGN KEY (edited_message_id) REFERENCES edited_message (id)');
        $this->addSql('ALTER TABLE user_chat ADD CONSTRAINT FK_1F1CBE63A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_chat ADD CONSTRAINT FK_1F1CBE631A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE telegram_update DROP FOREIGN KEY FK_EADEC7C56E6DB9');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E91A9A7125');
        $this->addSql('ALTER TABLE edited_message DROP FOREIGN KEY FK_7D194E541A9A7125');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F1A9A7125');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCEE99696');
        $this->addSql('ALTER TABLE user_chat DROP FOREIGN KEY FK_1F1CBE631A9A7125');
        $this->addSql('ALTER TABLE telegram_update DROP FOREIGN KEY FK_EADEC7BDB2861A');
        $this->addSql('ALTER TABLE telegram_update DROP FOREIGN KEY FK_EADEC7765896A');
        $this->addSql('ALTER TABLE telegram_update DROP FOREIGN KEY FK_EADEC7975AF2A3');
        $this->addSql('ALTER TABLE callback_query DROP FOREIGN KEY FK_D36CF3A11A9A7125537A1329');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F9FCD8AA732E801DC');
        $this->addSql('ALTER TABLE telegram_update DROP FOREIGN KEY FK_EADEC71A9A7125537A1329');
        $this->addSql('ALTER TABLE botan_shortener DROP FOREIGN KEY FK_717366FAA76ED395');
        $this->addSql('ALTER TABLE callback_query DROP FOREIGN KEY FK_D36CF3A1A76ED395');
        $this->addSql('ALTER TABLE chosen_inline_result DROP FOREIGN KEY FK_12A363A2A76ED395');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9A76ED395');
        $this->addSql('ALTER TABLE edited_message DROP FOREIGN KEY FK_7D194E54A76ED395');
        $this->addSql('ALTER TABLE inline_query DROP FOREIGN KEY FK_F43FD7D6A76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F3F638537');
        $this->addSql('ALTER TABLE user_chat DROP FOREIGN KEY FK_1F1CBE63A76ED395');
        $this->addSql('DROP TABLE botan_shortener');
        $this->addSql('DROP TABLE callback_query');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE chosen_inline_result');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE edited_message');
        $this->addSql('DROP TABLE inline_query');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE request_limiter');
        $this->addSql('DROP TABLE telegram_update');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_chat');
    }
}
