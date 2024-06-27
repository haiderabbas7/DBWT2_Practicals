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

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
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
        echo "\nUsers connected: ";
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

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
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



$app = new Ratchet\App('localhost', 8081);
$app->route('/beispiel', new BeispielBroadcaster(), array('*'));
$app->route('/maintenance', new MaintenanceBroadcaster(), array('*'));
$app->route('/articleSold', new ArticleSoldBroadcaster(), array('*'));
$app->route('/articleOnSale', new ArticleOnSaleBroadcaster(), array('*'));
$app->run();

