<?php

require 'C:/Users/Haider/PhpstormProjects/dbwt2/vendor/autoload.php';

\Ratchet\Client\connect('ws://localhost:8081')->then(function($conn) {
    $conn->send('Connected');
    $conn->close();
}, function ($e) {
    echo "Could not connect: {$e->getMessage()}\n";
});
