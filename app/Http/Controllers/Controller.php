<?php

namespace App\Http\Controllers;
use OpenApi\Attributes as OA;


#[
    OA\Info(version: "1.0.0", description: "News Aggregator API Documentation", title: "News Aggregator API"),
    OA\Server(url: "https://localhost:8000", description: "Development Server")
]
abstract class Controller
{
    //
}
