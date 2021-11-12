<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211108062034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brand (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1C52F95877153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `option` (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5A8600B077153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', brand_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', is_new TINYINT(1) NOT NULL, year INT NOT NULL, price NUMERIC(12, 2) NOT NULL, INDEX IDX_1B80E48644F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle_option (vehicle_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', option_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_F3580163545317D1 (vehicle_id), INDEX IDX_F3580163A7C41D6F (option_id), PRIMARY KEY(vehicle_id, option_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E48644F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vehicle_option ADD CONSTRAINT FK_F3580163545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vehicle_option ADD CONSTRAINT FK_F3580163A7C41D6F FOREIGN KEY (option_id) REFERENCES `option` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E48644F5D008');
        $this->addSql('ALTER TABLE vehicle_option DROP FOREIGN KEY FK_F3580163A7C41D6F');
        $this->addSql('ALTER TABLE vehicle_option DROP FOREIGN KEY FK_F3580163545317D1');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('DROP TABLE vehicle_option');
    }
}
