<?php

class Message {
    private $conn;
    
    private $id;
    private $user_id;
    private $name;
    private $email;
    private $number;
    private $message;

    public function __construct(PDO $conn, array $data = [])
    {
        $this->conn = $conn;

        if (!empty($data)) {
            $this->hydrate($data);
        }
    }

    private function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    // GETTERS
    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getNumber() {
        return $this->number;
    }

    public function getMessage() {
        return $this->message;
    }

    // SETTERS
    public function setId($id) {
        $this->id = $id;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setNumber($number) {
        $this->number = $number;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    // MÉTHODES BASE DE DONNÉES
    public function deleteMessage($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM `messages` WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function getAllMessages()
    {
        $stmt = $this->conn->prepare("SELECT * FROM `messages`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
