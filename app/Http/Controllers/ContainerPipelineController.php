<?php

namespace App\Http\Controllers;

use App\Pipelines\SanitizeInputPipe;
use App\Pipelines\ProfanityCensorPipe;
use App\Pipelines\EmojiSentimentEnricherPipe;
use App\Pipelines\MetadataStamperPipe;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class ContainerPipelineController extends Controller
{
    /**
     * Display Pipeline Studio.
     */
    public function index()
    {
        $availablePipes = [
            [
                'key' => 'sanitize',
                'name' => 'HTML & XSS Sanitizer',
                'class' => SanitizeInputPipe::class,
                'desc' => 'Strips HTML tags and normalizes whitespace',
                'checked' => true,
            ],
            [
                'key' => 'censor',
                'name' => 'Profanity & Spam Blocklist Censor',
                'class' => ProfanityCensorPipe::class,
                'desc' => 'Masks restricted tokens (badword, scam, spam) with ***',
                'checked' => true,
            ],
            [
                'key' => 'enrich',
                'name' => 'Sentiment & Context Badge Enricher',
                'class' => EmojiSentimentEnricherPipe::class,
                'desc' => 'Detects urgent/positive/issue mood and appends contextual badges',
                'checked' => true,
            ],
            [
                'key' => 'stamp',
                'name' => 'Audit & Request Metadata Stamper',
                'class' => MetadataStamperPipe::class,
                'desc' => 'Injects Illuminate\Http\Request via DI and appends audit UUID & IP',
                'checked' => true,
            ],
        ];

        return view('container.pipeline', compact('availablePipes'));
    }

    /**
     * Run input through Container-resolved pipeline.
     */
    public function run(Request $request)
    {
        $text = trim($request->input('text', '<b>Great product!</b> But fix this urgent bug asap, otherwise it feels like a scam.'));
        $selectedKeys = $request->input('pipes', ['sanitize', 'censor', 'enrich', 'stamp']);

        $pipeMap = [
            'sanitize' => SanitizeInputPipe::class,
            'censor' => ProfanityCensorPipe::class,
            'enrich' => EmojiSentimentEnricherPipe::class,
            'stamp' => MetadataStamperPipe::class,
        ];

        $pipesToRun = [];
        foreach ($selectedKeys as $key) {
            if (isset($pipeMap[$key])) {
                $pipesToRun[] = $pipeMap[$key];
            }
        }

        if (empty($pipesToRun)) {
            return response()->json(['success' => false, 'message' => 'Please select at least one pipeline stage.'], 422);
        }

        $payload = [
            'original_text' => $text,
            'current_text' => $text,
            'steps' => [],
            'metadata' => [],
        ];

        $startTime = microtime(true);

        // Execute through Laravel Container Pipeline
        $result = app(Pipeline::class)
            ->send($payload)
            ->through($pipesToRun)
            ->then(fn(array $finalPayload) => $finalPayload);

        $totalTimeMs = round((microtime(true) - $startTime) * 1000, 2);

        return response()->json([
            'success' => true,
            'original_text' => $result['original_text'],
            'final_text' => $result['current_text'],
            'total_pipes_executed' => count($result['steps']),
            'total_time_ms' => $totalTimeMs,
            'steps' => $result['steps'],
            'metadata' => $result['metadata'] ?? null,
        ]);
    }
}
