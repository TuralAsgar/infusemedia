<?php

namespace classes;

use PDO;

class Image
{
    private PDO $pdo;
    private string $imageID;
    private string $ipAddress;
    private string $userAgent;

    function __construct(string $imageID)
    {
        $this->pdo = Database::getInstance()->getConnection();
        $this->imageID = (int)$imageID;
    }

    public function setIpAddress(string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }

    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    public function increaseViewCount(): void
    {
        if ($this->hasUserAlreadySeen()) {
            $this->updateViewCount();
        } else {
            $this->insertViewCount();
        }
    }

    function getViewCount(): int
    {
        $sql = "SELECT view_count 
                FROM logs 
                WHERE image_id = :image_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':image_id', $this->imageID, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return (int)$result['view_count'];
        } else {
            return 0;
        }
    }

    function updateViewCount(): bool
    {
        $sql = "UPDATE logs SET view_date = CURRENT_TIMESTAMP, view_count = view_count + 1 WHERE image_id = :image_id AND ip_address = :ip_address AND user_agent = :user_agent";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':image_id', $this->imageID, PDO::PARAM_INT);
        $statement->bindParam(':ip_address', $this->ipAddress);
        $statement->bindParam(':user_agent', $this->userAgent);
        return $statement->execute();
    }

    function hasUserAlreadySeen(): bool
    {
        $sql = "SELECT * 
            FROM logs 
            WHERE image_id = :image_id 
              AND ip_address = :ip_address 
              AND user_agent = :user_agent";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':image_id', $this->imageID, PDO::PARAM_INT);
        $statement->bindParam(':ip_address', $this->ipAddress);
        $statement->bindParam(':user_agent', $this->userAgent);
        $statement->execute();

        return $statement->rowCount() > 0;
    }

    function insertViewCount(): bool
    {
        $sql = "INSERT INTO logs (ip_address, user_agent, view_date, image_id, view_count) VALUES (:ip_address, :user_agent, CURRENT_TIMESTAMP, :image_id, 1)";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':image_id', $this->imageID, PDO::PARAM_INT);
        $statement->bindParam(':ip_address', $this->ipAddress);
        $statement->bindParam(':user_agent', $this->userAgent);
        return $statement->execute();
    }
}