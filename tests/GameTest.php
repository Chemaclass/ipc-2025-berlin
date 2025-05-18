<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class GameTest extends TestCase
{
    #[DataProvider('providerGameRunner')]
    public function test_game_runner(int $seed, string $expected): void
    {
        srand($seed);
        ob_start();
        require __DIR__.'/../GameRunner.php';
        $output = ob_get_clean();

        self::assertSame($expected, $output);
    }

    public static function providerGameRunner(): iterable
    {
        foreach (range(1, 100) as $seed) {
            $filename = sprintf(__DIR__.'/tmp/runner-%s.txt', $seed);
            if (!file_exists($filename)) {
                self::createSnapshot($seed, $filename);
            }

            $content = file_get_contents($filename);

            yield [$seed, $content];
        }
    }

    private static function createSnapshot(int $seed, string $filename): void
    {
        srand($seed);
        ob_start();
        require __DIR__.'/../GameRunner.php';
        $content = ob_get_clean();
        file_put_contents($filename, $content);
    }
}
