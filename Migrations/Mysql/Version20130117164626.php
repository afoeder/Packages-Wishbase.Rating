<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Creates the basic migration schema for the Wishbase.Rating package
 */
class Version20130117164626 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE wishbase_rating_domain_model_abstractrating (persistence_object_identifier VARCHAR(40) NOT NULL, rater VARCHAR(40) DEFAULT NULL, creationdate DATETIME NOT NULL, dtype VARCHAR(255) NOT NULL, INDEX IDX_A1791D4E33D39BAA (rater), PRIMARY KEY(persistence_object_identifier))");
		$this->addSql("CREATE TABLE wishbase_rating_domain_model_rating (persistence_object_identifier VARCHAR(40) NOT NULL, value INT NOT NULL, PRIMARY KEY(persistence_object_identifier))");
		$this->addSql("ALTER TABLE wishbase_rating_domain_model_abstractrating ADD CONSTRAINT FK_A1791D4E33D39BAA FOREIGN KEY (rater) REFERENCES typo3_party_domain_model_abstractparty (persistence_object_identifier)");
		$this->addSql("ALTER TABLE wishbase_rating_domain_model_rating ADD CONSTRAINT FK_D6AD964E47A46B0A FOREIGN KEY (persistence_object_identifier) REFERENCES wishbase_rating_domain_model_abstractrating (persistence_object_identifier) ON DELETE CASCADE");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE wishbase_rating_domain_model_rating DROP FOREIGN KEY FK_D6AD964E47A46B0A");
		$this->addSql("DROP TABLE wishbase_rating_domain_model_abstractrating");
		$this->addSql("DROP TABLE wishbase_rating_domain_model_rating");
	}
}

?>