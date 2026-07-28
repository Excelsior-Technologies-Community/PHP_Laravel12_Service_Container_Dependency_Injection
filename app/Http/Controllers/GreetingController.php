<?php

namespace App\Http\Controllers;

use App\Factories\GreetingFactory;
use App\Factories\GreetingThemeFactory;
use App\Services\GreetingLoggerService;


class GreetingController extends Controller
{

    public function __construct(
        protected GreetingFactory $factory,
        protected GreetingThemeFactory $themeFactory,
        protected GreetingLoggerService $logger
    ) {}



    public function index()
    {

        $type = request()->get('type', 'english');

        $theme = request()->get('theme', 'professional');


        $service = $this->factory->make($type);


        $message = $service->greet();



        $themeService =
            $this->themeFactory->make($theme);



        $message =
            $themeService->format($message);



        $this->logger->log(
            $type,
            $message
        );


        return view('greeting', compact('message', 'type', 'theme'));
    }
}
