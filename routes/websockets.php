<?php
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;

WebSocketsRouter::webSocket('/app', \App\Http\Controllers\WebSocketController::class);