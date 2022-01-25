<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220125001147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE symfony_demo_product ADD author_id INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_Product_Author_Id ON symfony_demo_product (author_id)');
        $this->addSql('ALTER TABLE symfony_demo_product ADD CONSTRAINT FK_Product_Author_Id FOREIGN KEY (author_id) REFERENCES symfony_demo_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE symfony_demo_product DROP CONSTRAINT FK_Product_Author_Id');
        $this->addSql('ALTER TABLE symfony_demo_product DROP author_id');
    }
}
