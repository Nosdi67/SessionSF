<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240605094512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formations (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, nb_place INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formations_stagiaires (formations_id INT NOT NULL, stagiaires_id INT NOT NULL, INDEX IDX_6B12EB1C3BF5B0C2 (formations_id), INDEX IDX_6B12EB1C887A63F9 (stagiaires_id), PRIMARY KEY(formations_id, stagiaires_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modules (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, nom VARCHAR(50) NOT NULL, INDEX IDX_2EB743D7BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programme (id INT AUTO_INCREMENT NOT NULL, module_id INT NOT NULL, formation_id INT DEFAULT NULL, nb_jour INT NOT NULL, INDEX IDX_3DDCB9FFAFC2B591 (module_id), INDEX IDX_3DDCB9FF5200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stagiaires (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, date_naissance DATETIME NOT NULL, sexe VARCHAR(20) NOT NULL, ville VARCHAR(50) NOT NULL, telephone VARCHAR(30) NOT NULL, email VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE formations_stagiaires ADD CONSTRAINT FK_6B12EB1C3BF5B0C2 FOREIGN KEY (formations_id) REFERENCES formations (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formations_stagiaires ADD CONSTRAINT FK_6B12EB1C887A63F9 FOREIGN KEY (stagiaires_id) REFERENCES stagiaires (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modules ADD CONSTRAINT FK_2EB743D7BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FFAFC2B591 FOREIGN KEY (module_id) REFERENCES modules (id)');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FF5200282E FOREIGN KEY (formation_id) REFERENCES formations (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formations_stagiaires DROP FOREIGN KEY FK_6B12EB1C3BF5B0C2');
        $this->addSql('ALTER TABLE formations_stagiaires DROP FOREIGN KEY FK_6B12EB1C887A63F9');
        $this->addSql('ALTER TABLE modules DROP FOREIGN KEY FK_2EB743D7BCF5E72D');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FFAFC2B591');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FF5200282E');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE formations');
        $this->addSql('DROP TABLE formations_stagiaires');
        $this->addSql('DROP TABLE modules');
        $this->addSql('DROP TABLE programme');
        $this->addSql('DROP TABLE stagiaires');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
