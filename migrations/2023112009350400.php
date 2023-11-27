<?php declare(strict_types=1);

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

return new class {

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     *
     * @return void
     */
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('users');
        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true]);
        $table->addColumn('username', Types::STRING, ['length' => 255]);
        $table->addColumn('password', Types::STRING, ['length' => 60]);
        $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
        $table->setPrimaryKey(['id']);
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     *
     * @return void
     */
    public function down(Schema $schema): void
    {
        echo get_class($this) . ' "down" method called' . PHP_EOL;
    }
};
