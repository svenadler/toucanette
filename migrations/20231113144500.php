<?php declare(strict_types=1);

return new class
{
    /**
     * @return void
     */
    public function up(): void
    {
        echo get_class($this) . ' "up" method called' . PHP_EOL;
    }

    /**
     * @return void
     */
    public function down(): void
    {
        echo get_class($this) . ' "down" method called' . PHP_EOL;
    }
};