<?php 
require_once './app/core/Repository.php';
require_once './app/entities/Participation.php';

class ParticipationRepository {
    private $pdo;

    public function __construct() {
        $this->pdo = Repository::getInstance()->getPDO();
    }

    public function findAll(): array {
        $stmt = $this->pdo->query('SELECT * FROM "Participation"');
        $participations = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $participations[] = $this->createParticipationFromRow($row);
        }
        return $participations;
    }

    private function createParticipationFromRow(array $row): Participation {
        return new Participation($row['id_user'], $row['id_event'], $row['date_inscription']);
    }

    public function create(Participation $participation): void {
        $stmt = $this->pdo->prepare('INSERT INTO "Participation" (id_user, id_event, date_inscription) VALUES (:id_user, :id_event, :date_inscription)');
        $stmt->bindParam(':id_user', $participation->getId_user(), PDO::PARAM_INT);
        $stmt->bindParam(':id_event', $participation->getId_event(), PDO::PARAM_INT);
        $stmt->bindParam(':date_inscription', $participation->getDate_inscription()->format('Y-m-d H:i:s'));
        $stmt->execute();
    }

    public function update(Participation $participation): void {
        $stmt = $this->pdo->prepare('UPDATE "Participation" SET id_user = :id_user, id_event = :id_event, date_inscription = :date_inscription WHERE id_user = :id_user AND id_event = :id_event');
        $stmt->bindParam(':id_user', $participation->getId_user(), PDO::PARAM_INT);
        $stmt->bindParam(':id_event', $participation->getId_event(), PDO::PARAM_INT);
        $stmt->bindParam(':date_inscription', $participation->getDate_inscription()->format('Y-m-d H:i:s'));
        $stmt->execute();
    }

    public function findById(int $id_user, int $id_event): ?Participation {
        $stmt = $this->pdo->prepare('SELECT * FROM "Participation" WHERE id_user = :id_user AND id_event = :id_event');
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':id_event', $id_event, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->createParticipationFromRow($row) : null;
    }

    public function delete(int $id_user, int $id_event): void {
        $stmt = $this->pdo->prepare('DELETE FROM "Participation" WHERE id_user = :id_user AND id_event = :id_event');
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':id_event', $id_event, PDO::PARAM_INT);
        $stmt->execute();
    }
}