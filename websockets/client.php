<?php

require __DIR__ . '/vendor/autoload.php';

\Ratchet\Client\connect('ws://localhost:8081/chat')->then(function($conn) {
    $conn->on('message', function($msg) use ($conn) {
        echo "Received: {$msg}\n";
        $conn->close();
    });
    $conn->send('Hello to everyone!');
    $conn->close();
}, function ($e) {
    echo "Could not connect: {$e->getMessage()}\n";
});
