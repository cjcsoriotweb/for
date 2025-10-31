<?php

namespace App\Http\Controllers\Clean\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class SuperadminTestController extends Controller
{
    private const STATUS_PASSED = 'passed';

    private const STATUS_FAILED = 'failed';

    public function __construct(
        private readonly Filesystem $filesystem,
    ) {}

    public function index(): View
    {
        return view('out-application.superadmin.superadmin-tests-page', [
            'tests' => $this->discoverTests()->values(),
        ]);
    }

    public function run(Request $request): JsonResponse
    {
        $tests = $this->discoverTests();

        if ($tests->isEmpty()) {
            return response()->json([
                'tests' => [],
                'status' => 'empty',
            ]);
        }

        $results = [];
        $overallStatus = self::STATUS_PASSED;

        foreach ($tests as $test) {
            $result = $this->runTest($test);
            $results[] = $result;

            if ($result['status'] === self::STATUS_FAILED) {
                $overallStatus = self::STATUS_FAILED;
            }
        }

        return response()->json([
            'tests' => $results,
            'status' => $overallStatus,
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection<int, array{ name: string, path: string }>
     */
    private function discoverTests(): Collection
    {
        $paths = collect(config('superadmin-tests.paths', []));

        return $paths
            ->filter(fn ($path) => $path && $this->filesystem->isDirectory($path))
            ->flatMap(fn ($path) => $this->filesystem->allFiles($path))
            ->filter(fn ($file) => Str::lower($file->getExtension()) === 'php')
            ->sortBy(fn ($file) => $file->getFilename())
            ->map(fn ($file) => [
                'name' => Str::headline(Str::replaceLast('.php', '', $file->getFilename())),
                'path' => trim(str_replace(base_path().DIRECTORY_SEPARATOR, '', $file->getPathname()), DIRECTORY_SEPARATOR),
            ])
            ->values();
    }

    /**
     * @param  array{name: string, path: string}  $test
     * @return array{name: string, path: string, status: string, output: string, duration: float}
     */
    private function runTest(array $test): array
    {
        $process = new Process([
            $this->phpBinary(),
            'artisan',
            'test',
            $test['path'],
            '--stop-on-failure',
            '--colors=never',
        ], base_path(), null, null, 120);
        $start = microtime(true);

        $process->run();

        $duration = round(microtime(true) - $start, 3);
        $status = $process->isSuccessful() ? self::STATUS_PASSED : self::STATUS_FAILED;

        return [
            'name' => $test['name'],
            'path' => $test['path'],
            'status' => $status,
            'output' => trim($process->getOutput().PHP_EOL.$process->getErrorOutput()),
            'duration' => $duration,
        ];
    }

    private function phpBinary(): string
    {
        $finder = new PhpExecutableFinder;

        return $finder->find(false) ?: PHP_BINARY;
    }
}
