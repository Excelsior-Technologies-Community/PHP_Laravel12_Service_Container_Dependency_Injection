<?php

namespace App\Http\Controllers;

use App\Services\Benchmark\TransientReportService;
use App\Services\Benchmark\SingletonReportService;
use App\Services\Benchmark\ScopedReportService;
use Illuminate\Http\Request;

class ContainerBenchmarkController extends Controller
{
    /**
     * Display Lifecycle Benchmark Lab.
     */
    public function index()
    {
        return view('container.benchmark');
    }

    /**
     * Execute live benchmark comparing bind() vs singleton() vs scoped().
     */
    public function run(Request $request)
    {
        $iterations = (int) $request->input('iterations', 500);
        $iterations = max(50, min(2500, $iterations));

        // 1. Benchmark Transient Bind ($app->bind)
        gc_collect_cycles();
        $memStartTransient = memory_get_usage();
        $timeStartTransient = microtime(true);
        $transientIds = [];

        for ($i = 0; $i < $iterations; $i++) {
            $obj = app(TransientReportService::class);
            $transientIds[$obj->getInstanceId()] = true;
        }

        $timeTransient = round((microtime(true) - $timeStartTransient) * 1000, 2);
        $memTransient = round((memory_get_usage() - $memStartTransient) / 1024, 2);
        $distinctTransient = count($transientIds);

        // 2. Benchmark Singleton ($app->singleton)
        gc_collect_cycles();
        $memStartSingleton = memory_get_usage();
        $timeStartSingleton = microtime(true);
        $singletonIds = [];

        for ($i = 0; $i < $iterations; $i++) {
            $obj = app(SingletonReportService::class);
            $singletonIds[$obj->getInstanceId()] = true;
        }

        $timeSingleton = round((microtime(true) - $timeStartSingleton) * 1000, 2);
        $memSingleton = round((memory_get_usage() - $memStartSingleton) / 1024, 2);
        $distinctSingleton = count($singletonIds);

        // 3. Benchmark Scoped ($app->scoped)
        gc_collect_cycles();
        $memStartScoped = memory_get_usage();
        $timeStartScoped = microtime(true);
        $scopedIds = [];

        for ($i = 0; $i < $iterations; $i++) {
            $obj = app(ScopedReportService::class);
            $scopedIds[$obj->getInstanceId()] = true;
        }

        $timeScoped = round((microtime(true) - $timeStartScoped) * 1000, 2);
        $memScoped = round((memory_get_usage() - $memStartScoped) / 1024, 2);
        $distinctScoped = count($scopedIds);

        return response()->json([
            'success' => true,
            'iterations' => $iterations,
            'results' => [
                'bind' => [
                    'label' => 'Transient ($app->bind)',
                    'binding_type' => 'bind()',
                    'distinct_instances' => $distinctTransient,
                    'time_ms' => $timeTransient,
                    'memory_kb' => max(0, $memTransient),
                    'sample_id' => array_key_first($transientIds),
                    'behavior' => "Re-instantiates fresh object on EVERY resolution ({$distinctTransient} unique objects created).",
                ],
                'singleton' => [
                    'label' => 'Shared Singleton ($app->singleton)',
                    'binding_type' => 'singleton()',
                    'distinct_instances' => $distinctSingleton,
                    'time_ms' => $timeSingleton,
                    'memory_kb' => max(0, $memSingleton),
                    'sample_id' => array_key_first($singletonIds),
                    'behavior' => "Resolved once, cached in container memory, and reused {$iterations} times (1 shared object).",
                ],
                'scoped' => [
                    'label' => 'Scoped Singleton ($app->scoped)',
                    'binding_type' => 'scoped()',
                    'distinct_instances' => $distinctScoped,
                    'time_ms' => $timeScoped,
                    'memory_kb' => max(0, $memScoped),
                    'sample_id' => array_key_first($scopedIds),
                    'behavior' => "Single instance per HTTP request lifecycle, then flushed automatically when request finishes.",
                ],
            ],
        ]);
    }
}
