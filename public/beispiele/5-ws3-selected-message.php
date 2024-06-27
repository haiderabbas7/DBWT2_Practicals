<?php

require 'C:/Users/Haider/PhpstormProjects/dbwt2/vendor/autoload.php';

\Ratchet\Client\connect('ws://localhost:8081')->then(function($conn) {
    $conn->on('message', function($msg) use ($conn) {
        $msg = json_decode($msg);
        $id = 0;
        if($msg->id == $id){
            echo "<h1>$msg->text</h1>";
        }
        else{
            echo "<h1>Wrong ID</h1>";
        }
        $conn->close();
    });
}, function ($e) {
    echo "Could not connect: {$e->getMessage()}\n";
});
