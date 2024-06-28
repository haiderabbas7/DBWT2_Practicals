<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require __DIR__ . '/vendor/autoload.php';


class BeispielBroadcaster implements MessageComponentInterface{
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        $msg = [
            'id' => '0',
            'text' => 'yo'
        ];
        foreach ($this->clients as $client){
            $client->send(json_encode($msg));
        }
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo 'Message received: ' . $msg;
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}


class MaintenanceBroadcaster implements MessageComponentInterface{
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    private function printConnectedUsers(){
        echo "\nUsers connected: ";
        foreach ($this->clients as $conn) {
            $value = $this->clients[$conn];
            echo "$value ";
        }
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        //HAB DAS AUSGESTELLT, DIE GANZEN NACHRICHTEN FUCKEN SO AB
        /*$data = [
            'message' => "In Kürze verbessern wir Abalo für Sie!\nNach einer kurzen Pause sind wir wieder\nfür Sie da! Versprochen."
        ];
        $json = json_encode($data);
        foreach ($this->clients as $client) {
            $client->send($json);
        }*/
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo 'Message received: ' . $msg;
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}


class ArticleSoldBroadcaster implements MessageComponentInterface{
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    private function printConnectedUsers(){
        echo "\nUsers connected to ArticleSold: ";
        foreach ($this->clients as $conn) {
            $value = $this->clients[$conn];
            echo "$value ";
        }
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn, -1);
        echo "\nopen";
        $this->printConnectedUsers();
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        //guck nach, falls noch keine userID für diesen client gespeichert wurde. falls ja, dann speichere die $msg als ID
        if ($this->clients[$from] == -1 && is_numeric($msg)) {
            $this->clients->detach($from);
            $this->clients->attach($from, intval($msg));
        }
        //versucht die nachricht als JSON zu decoden
        $decodedMsg = json_decode($msg);
        //wenn es ein JSON ist...
        if (json_last_error() == JSON_ERROR_NONE) {
            //..und das Attribut fromApplication gesetzt ist, so handelt es sich um eine Nachricht von der Anwendung
            if (isset($decodedMsg->fromApplication) && $decodedMsg->fromApplication === true) {
                $userID = $decodedMsg->msg;
                $article = $decodedMsg->article;
                $client = null;
                foreach ($this->clients as $cl) {
                    if ($this->clients[$cl] == $userID) {
                        $client = $cl;
                        break;
                    }
                }
                if ($client !== null) {
                    $data = [
                        'message' => "Großartig! Ihr Artikel $article wurde erfolgreich verkauf!"
                    ];
                    $json = json_encode($data);
                    $client->send($json);
                }
            }
        }
        echo "\nmessage: $msg";
        $this->printConnectedUsers();
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "\nclose";
        $this->printConnectedUsers();
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
        echo "\nerror";
        $this->printConnectedUsers();
    }
}


class ArticleOnSaleBroadcaster implements MessageComponentInterface{
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    private function printConnectedUsers(){
        echo "\nUsers connected to ArticleOnSale: ";
        foreach ($this->clients as $conn) {
            $userId = $this->clients[$conn]['userId'];
            $articleIds = $this->clients[$conn]['articleIds'];
            $articleIds_string = implode(", ", $articleIds);
            echo "$userId [$articleIds_string], ";
        }
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn, ['userId' => -1, 'articleIds' => []]);
        echo "\nopen";
        $this->printConnectedUsers();
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        //guck nach, falls noch keine userID für diesen client gespeichert wurde. falls ja, dann speichere die $msg als ID
        if ($this->clients[$from]['userId'] == -1 && is_numeric($msg)) {
            $this->clients->detach($from);
            $this->clients->attach($from, ['userId' => intval($msg), 'articleIds' => []]);
        }
        $decodedMsg = json_decode($msg);
        //wenn es ein JSON ist...
        if (json_last_error() == JSON_ERROR_NONE) {
            //..und das Attribut fromApplication gesetzt ist, so handelt es sich um eine Nachricht von der Anwendung
            if (isset($decodedMsg->fromApplication) && $decodedMsg->fromApplication === true) {
                //FLOW 1: guck notizen bin zu faul kommentare zu schreiben
                if($decodedMsg->opcode === 1){
                    $userID = $decodedMsg->msg;
                    $articleIds = $decodedMsg->article;
                    foreach ($this->clients as $client) {
                        if ($this->clients[$client]['userId'] == $userID) {
                            $this->clients->detach($client);
                            $this->clients->attach($client, ['userId' => $userID, 'articleIds' => $articleIds]);
                            break;
                        }
                    }
                }
                //FLOW 2: guck notizen bin zu faul kommentare zu schreiben
                else if($decodedMsg->opcode === 2){
                    $articleId = $decodedMsg->msg;
                    $article = $decodedMsg->article;

                    /*
                     * Hier soll passieren:
                     * aus $clients alle clients rausholen, die $articleId in ihrer $articleIds array gespeichert haben
                     * rausholen also zwischenspeichern
                     * und nachdem man alle clients durchgegangen ist, Nachricht zusammenbauen wie in Aufg12
                     * Und an alle gefundenen clients die nachricht schicken
                     * */
                    $watching_clients = [];
                    foreach ($this->clients as $client) {
                        if (in_array($articleId, $this->clients[$client]['articleIds'])) {
                            $watching_clients[] = $client;
                        }
                    }
                    if ($watching_clients !== null) {
                        $data = [
                            'message' => "Der Artikel $article wird nun günstiger angeboten! Greifen Sie schnell zu.",
                            'articleId' => $articleId
                        ];
                        $json = json_encode($data);
                        foreach ($watching_clients as $client) {
                            $client->send($json);
                        }
                    }
                }
            }
        }
        echo "\nmessage: $msg";
        $this->printConnectedUsers();
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}


$app = new Ratchet\App('localhost', 8081);
$app->route('/beispiel', new BeispielBroadcaster(), array('*'));
$app->route('/maintenance', new MaintenanceBroadcaster(), array('*'));
$app->route('/articleSold', new ArticleSoldBroadcaster(), array('*'));
$app->route('/articleOnSale', new ArticleOnSaleBroadcaster(), array('*'));
$app->run();

