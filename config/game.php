<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Game Server Admin Page Socket
    |--------------------------------------------------------------------------
    |
    | Connection settings for the game server's admin page TCP socket.
    | The game server accepts commands after password authentication.
    |
    */

    'adminpage_host' => env('GAME_ADMINPAGE_HOST', '127.0.0.1'),
    'adminpage_port' => (int) env('GAME_ADMINPAGE_PORT', 13000),
    'adminpage_password' => env('GAME_ADMINPAGE_PASSWORD', ''),
    'adminpage_timeout' => (int) env('GAME_ADMINPAGE_TIMEOUT', 3),

];
