<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201004091436 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE AllWards');
        $this->addSql('DROP TABLE PD2019');
        $this->addSql('DROP INDEX `index` ON roadgroup');
        $this->addSql('ALTER TABLE roadgroup DROP divisionid, CHANGE roadgroupid roadgroupid VARCHAR(255) NOT NULL, CHANGE wardid wardid VARCHAR(255) NOT NULL, CHANGE subwardid subwardid VARCHAR(255) NOT NULL, CHANGE name name VARCHAR(20) NOT NULL, CHANGE priority Priority DOUBLE PRECISION DEFAULT NULL, CHANGE prioritygroup prioritygroup VARCHAR(4) DEFAULT NULL, CHANGE minlat minlat DOUBLE PRECISION DEFAULT NULL, CHANGE maxlat maxlat DOUBLE PRECISION DEFAULT NULL, CHANGE minlong minlong DOUBLE PRECISION DEFAULT NULL, CHANGE maxlong maxlong DOUBLE PRECISION DEFAULT NULL, CHANGE midlat midlat DOUBLE PRECISION DEFAULT NULL, CHANGE midlong midlong DOUBLE PRECISION DEFAULT NULL, ADD PRIMARY KEY (roadgroupid)');
        $this->addSql('ALTER TABLE street CHANGE qualifier qualifier VARCHAR(4) DEFAULT NULL, CHANGE wardid wardid VARCHAR(10) DEFAULT NULL, CHANGE roadgroupid roadgroupid VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE subward CHANGE subwardid subwardid VARCHAR(255) NOT NULL, CHANGE subward subward VARCHAR(50) NOT NULL, CHANGE wardid wardid VARCHAR(255) NOT NULL, ADD PRIMARY KEY (subwardid)');
        $this->addSql('DROP INDEX `index` ON ward');
        $this->addSql('ALTER TABLE ward CHANGE wardid wardid VARCHAR(255) NOT NULL, CHANGE ward ward VARCHAR(50) NOT NULL, ADD PRIMARY KEY (wardid)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE AllWards (DistrictWard VARCHAR(10) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, SubGroup VARCHAR(18) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, GroupCode VARCHAR(6) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, Street VARCHAR(38) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, Households INT DEFAULT NULL, PD VARCHAR(8) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE PD2019 (PDID VARCHAR(3) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, Address VARCHAR(97) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, PDCode VARCHAR(3) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, Latitude NUMERIC(8, 6) DEFAULT NULL, Longitude NUMERIC(7, 6) DEFAULT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE roadgroup DROP INDEX primary, ADD UNIQUE INDEX index (roadgroupid)');
        $this->addSql('ALTER TABLE roadgroup ADD divisionid VARCHAR(3) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE roadgroupid roadgroupid VARCHAR(6) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE name name VARCHAR(38) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE subwardid subwardid VARCHAR(7) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE wardid wardid VARCHAR(10) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE minlat minlat VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE midlat midlat VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE maxlat maxlat VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE minlong minlong VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE midlong midlong VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE maxlong maxlong VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE Priority priority VARCHAR(1) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE prioritygroup prioritygroup VARCHAR(5) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE street CHANGE qualifier qualifier VARCHAR(50) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE wardid wardid VARCHAR(10) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE roadgroupid roadgroupid VARCHAR(10) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE subward DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE subward CHANGE subwardid subwardid VARCHAR(20) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE subward subward VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE wardid wardid VARCHAR(20) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE ward MODIFY wardid VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ward DROP INDEX primary, ADD UNIQUE INDEX index (wardid)');
        $this->addSql('ALTER TABLE ward CHANGE wardid wardid VARCHAR(20) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE ward ward VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
    }
}
