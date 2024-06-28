<?php
namespace App\Http\Controllers;
//dirname geht basically ne ebene höher idk
require dirname(__DIR__, 3) . '/vendor/autoload.php';


/**
 * Diese Klasse regelt die Anwendung <-> Broadcaster Kommunikation
 * Also hier öffnen wir die Verbindung, behalten sie aufrecht und können hier dem Broadcaster durch Opcodes kontrollieren
 * und damit Nachrichten an die Clients verschicken lassen
 */
class WebSocketApplicationController
{
    //Kümmert sich darum, die Nachricht als JSON zu verpacken und als Nachricht von der Application zu labeln
    private function jsonEncoder($msg, $article, $opcode): false|string {
        $data = [
            "fromApplication" => true,
            "opcode" => $opcode,
            "msg" => $msg,
            "article" => $article
        ];
        return json_encode($data);
    }

    public function sendMaintenanceMessage($msg, $article, $opcode=0): void{
        \Ratchet\Client\connect('ws://localhost:8081/maintenance')->then(function($conn) use ($msg, $article, $opcode) {
            $jsonEncoded = $this->jsonEncoder($msg, $article, $opcode);
            $conn->send($jsonEncoded);
            $conn->close();
        }, function ($e) {
            echo "Could not connect: {$e->getMessage()}\n";
        });
    }

    public function sendArticleSoldMessage($msg, $article, $opcode=0): void{
        \Ratchet\Client\connect('ws://localhost:8081/articleSold')->then(function($conn) use ($msg, $article, $opcode) {
            $jsonEncoded = $this->jsonEncoder($msg, $article, $opcode);
            $conn->send($jsonEncoded);
            $conn->close();
        }, function ($e) {
            echo "Could not connect: {$e->getMessage()}\n";
        });
    }

    public function sendArticleOnSaleMessage($msg, $article, $opcode=0): void{
        \Ratchet\Client\connect('ws://localhost:8081/articleOnSale')->then(function($conn) use ($msg, $article, $opcode) {
            $jsonEncoded = $this->jsonEncoder($msg, $article, $opcode);
            $conn->send($jsonEncoded);
            $conn->close();
        }, function ($e) {
            echo "Could not connect: {$e->getMessage()}\n";
        });
    }
}
