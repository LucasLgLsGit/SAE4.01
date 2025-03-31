<?php 
require_once './app/core/Repository.php';
require_once './app/entities/Participation.php';

class ParticipationRepository {
    private $pdo;

    public function __construct() {
        $this->pdo = Repository::getInstance()->getPDO();
    }

    public function findAll(): array {
        $stmt = $this->pdo->query('SELECT * FROM "participation"');
        $participations = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $participations[] = $this->createParticipationFromRow($row);
        }
        return $participations;
    }

    private function createParticipationFromRow(array $row): Participation {
        return new Participation(   $row['id_user'], 
                                    $row['id_event'], 
                                    new DateTime($row['date_inscription']));
    }

    public function create(Participation $participation): void {
        $stmt = $this->pdo->prepare('INSERT INTO "participation" (id_user, id_event, date_inscription) VALUES (:id_user, :id_event, :date_inscription)');
        $stmt->execute([
            'id_user' => $participation->getId_user(),
            'id_event' => $participation->getId_event(),
            'date_inscription' => $participation->getDate_inscription()->format('Y-m-d H:i:s')
        ]);
    }

    public function update(Participation $participation): void {
        $stmt = $this->pdo->prepare('UPDATE "participation" SET id_user = :id_user, id_event = :id_event, date_inscription = :date_inscription WHERE id_user = :id_user AND id_event = :id_event');
        $stmt->execute([
            'id_user' => $participation->getId_user(),
            'id_event' => $participation->getId_event(),
            'date_inscription' => $participation->getDate_inscription()->format('Y-m-d H:i:s')
        ]);
    }

    public function findById(int $id_user, int $id_event): ?Participation {
        $stmt = $this->pdo->prepare('SELECT * FROM "participation" WHERE id_user = :id_user AND id_event = :id_event');
        $stmt->execute([
            'id_user' => $id_user,
            'id_event' => $id_event
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->createParticipationFromRow($row) : null;
    }

    public function delete(int $id_user, int $id_event): void {
        $stmt = $this->pdo->prepare('DELETE FROM "participation" WHERE id_user = :id_user AND id_event = :id_event');
        $stmt->execute([
            'id_user' => $id_user,
            'id_event' => $id_event
        ]);
    }
}