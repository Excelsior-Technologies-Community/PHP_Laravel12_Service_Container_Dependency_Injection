<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ReflectionClass;
use ReflectionMethod;

class ContainerVisualizerController extends Controller
{
    /**
     * Display the Service Container Visualizer & Diagnostics Studio.
     */
    public function index(Request $request)
    {
        $app = app();
        $search = strtolower(trim($request->input('search', '')));
        $filterType = $request->input('filter', 'all');

        $bindings = $app->getBindings();
        $instances = (function () {
            return $this->instances;
        })->bindTo($app, \Illuminate\Container\Container::class)();
        $aliases = (function () {
            return $this->aliases;
        })->bindTo($app, \Illuminate\Container\Container::class)();

        $rows = [];
        foreach ($bindings as $abstract => $binding) {
            $isShared = $binding['shared'] ?? false;
            $isResolved = isset($instances[$abstract]);

            // Determine category
            $category = 'Framework';
            if (str_starts_with($abstract, 'App\\')) {
                $category = 'Application';
            } elseif (str_starts_with($abstract, 'Illuminate\\Contracts\\')) {
                $category = 'Contracts';
            }

            // Apply filter
            if ($filterType === 'app' && $category !== 'Application') {
                continue;
            }
            if ($filterType === 'singleton' && !$isShared) {
                continue;
            }
            if ($filterType === 'resolved' && !$isResolved) {
                continue;
            }

            // Apply search
            if ($search !== '' && !str_contains(strtolower($abstract), $search)) {
                continue;
            }

            $rows[] = [
                'abstract' => $abstract,
                'category' => $category,
                'is_shared' => $isShared,
                'is_resolved' => $isResolved,
                'concrete_type' => is_string($binding['concrete'] ?? null) ? $binding['concrete'] : 'Closure / Factory',
            ];
        }

        // Summary Counts
        $totalBindings = count($bindings);
        $totalSingletons = count(array_filter($bindings, fn($b) => $b['shared'] ?? false));
        $totalResolved = count($instances);
        $totalAliases = count($aliases);

        return view('container.visualizer', compact(
            'rows',
            'search',
            'filterType',
            'totalBindings',
            'totalSingletons',
            'totalResolved',
            'totalAliases'
        ));
    }

    /**
     * Resolve any requested abstract from the container and inspect reflection metadata.
     */
    public function resolve(Request $request)
    {
        $abstract = trim($request->input('abstract', $request->input('target', '')));

        if (empty($abstract)) {
            return response()->json(['success' => false, 'message' => 'Please provide a valid class or interface name.'], 422);
        }

        $app = app();
        $start = microtime(true);

        try {
            $instance = $app->make($abstract);
            $durationMs = round((microtime(true) - $start) * 1000, 3);

            $resolvedClass = get_class($instance);
            $objectId = spl_object_id($instance);
            $isShared = $app->isShared($abstract);

            // Reflection Inspection
            $reflector = new ReflectionClass($resolvedClass);

            // Inspect Constructor Parameters
            $constructorParams = [];
            $constructor = $reflector->getConstructor();
            if ($constructor) {
                foreach ($constructor->getParameters() as $param) {
                    $type = $param->getType();
                    $constructorParams[] = [
                        'name' => '$' . $param->getName(),
                        'type' => $type ? $type->getName() : 'mixed',
                        'is_optional' => $param->isOptional(),
                        'has_default' => $param->isDefaultValueAvailable(),
                        'default' => $param->isDefaultValueAvailable() ? json_encode($param->getDefaultValue()) : null,
                    ];
                }
            }

            // Public Methods
            $publicMethods = array_map(
                fn(ReflectionMethod $m) => $m->getName(),
                array_slice($reflector->getMethods(ReflectionMethod::IS_PUBLIC), 0, 8)
            );

            // Implemented Interfaces
            $interfaces = array_values($reflector->getInterfaceNames());

            return response()->json([
                'success' => true,
                'abstract' => $abstract,
                'resolved_class' => $resolvedClass,
                'object_id' => $objectId,
                'is_shared' => $isShared,
                'duration_ms' => $durationMs,
                'file' => str_replace(base_path() . DIRECTORY_SEPARATOR, '', $reflector->getFileName() ?: 'Built-in'),
                'line' => $reflector->getStartLine(),
                'interfaces' => $interfaces,
                'parameters' => $constructorParams,
                'methods' => $publicMethods,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'abstract' => $abstract,
                'message' => 'Container Resolution Failed: ' . $e->getMessage(),
            ], 400);
        }
    }
}
