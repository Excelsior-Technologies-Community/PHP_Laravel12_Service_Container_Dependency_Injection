<?php

namespace App\Http\Controllers;

use App\Factories\GreetingFactory;
use App\Factories\GreetingThemeFactory;
use App\Models\GreetingHistory;
use App\Services\GreetingLoggerService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GreetingController extends Controller
{
    public function __construct(
        protected GreetingFactory $factory,
        protected GreetingThemeFactory $themeFactory,
        protected GreetingLoggerService $logger
    ) {}

    /**
     * Greeting Page
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'english');
        $theme = $request->get('theme', 'professional');

        // Resolve Greeting Service
        $service = $this->factory->make($type);

        $message = $service->greet();

        // Resolve Theme Service
        $themeService = $this->themeFactory->make($theme);

        $message = $themeService->format($message);

        // Log Greeting
        $this->logger->log($type, $message);

        // Save Greeting History
        GreetingHistory::create([
            'greeting_type' => $type,
            'theme' => $theme,
            'message' => $message,
        ]);

        // Search
        $search = $request->search;

        $histories = GreetingHistory::when($search, function ($query) use ($search) {

            $query->where('greeting_type', 'like', "%{$search}%")
                ->orWhere('theme', 'like', "%{$search}%")
                ->orWhere('message', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        // Statistics
        $stats = [

            'total' => GreetingHistory::count(),

            'english' => GreetingHistory::where('greeting_type', 'english')->count(),

            'formal' => GreetingHistory::where('greeting_type', 'formal')->count(),

            'motivational' => GreetingHistory::where('greeting_type', 'motivational')->count(),

            'professional' => GreetingHistory::where('theme', 'professional')->count(),

            'fun' => GreetingHistory::where('theme', 'fun')->count(),

        ];

        return view('greeting', compact(
            'message',
            'type',
            'theme',
            'histories',
            'stats',
            'search'
        ));
    }

    /**
     * Export Greeting History CSV
     */
    public function export()
    {
        $fileName = 'greeting-history.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () {

            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID',
                'Greeting Type',
                'Theme',
                'Message',
                'Created At',
            ]);

            GreetingHistory::latest()->chunk(100, function ($rows) use ($file) {

                foreach ($rows as $row) {

                    fputcsv($file, [

                        $row->id,

                        $row->greeting_type,

                        $row->theme,

                        strip_tags($row->message),

                        $row->created_at,

                    ]);
                }
            });

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }


    /**
     * Delete Single Greeting History
     */
    public function destroy(GreetingHistory $history)
    {
        $history->delete();

        return redirect()
            ->route('greet.index')
            ->with('success', 'Greeting history deleted successfully.');
    }

    /**
     * Delete All Greeting History
     */
    public function destroyAll()
    {
        GreetingHistory::truncate();

        return redirect()
            ->route('greet.index')
            ->with('success', 'All greeting history deleted successfully.');
    }
}
