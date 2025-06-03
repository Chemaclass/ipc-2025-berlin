<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class GoldenMasterTest extends TestCase
{
    #[DataProvider('providerGameRunner')]
    public function test_game_runner(int $seed): void
    {
        $filename = sprintf(__DIR__.'/tmp/runner-%s.txt', $seed);
        if (!file_exists($filename)) {
            file_put_contents($filename, $this->generateOutput($seed));
        }
        $expected = file_get_contents($filename);

        self::assertSame($expected, $this->generateOutput($seed));
    }

    public static function providerGameRunner(): iterable
    {
        foreach (range(1, 100) as $seed) {
            yield [$seed];
        }
    }

    private function generateOutput(int $seed): string
    {
        srand($seed);
        ob_start();
        require __DIR__.'/../GameRunner.php';

        return (string) ob_get_clean();
    }
}
