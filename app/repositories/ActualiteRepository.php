<?php
require_once './app/core/Repository.php';
require_once './app/entities/Actualite.php';

class ActualiteRepository {
    private $pdo;

    public function __construct() {
        $this->pdo = Repository::getInstance()->getPDO();
    }

    public function findAll(): array {
        $stmt = $this->pdo->query('SELECT * FROM "Actualite"');
        $actualites = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $actualites[] = $this->createActualiteFromRow($row);
        }
        return $actualites;
    }

    private function createActualiteFromRow(array $row): Actualite {
        return new Actualite($row['id_actu'], $row['titre_article'], $row['contenu'], $row['date_publication'], $row['id_user']);
    }

    public function create(Actualite $actualite): void {
        $stmt = $this->pdo->prepare('INSERT INTO "Actualite" (titre_article, contenu, date_publication, id_user) VALUES (:titre_article, :contenu, :date_publication, :id_user)');
        $stmt->bindParam(':titre_article', $actualite->getTitreArticle());
        $stmt->bindParam(':contenu', $actualite->getContenu());
        $stmt->bindParam(':date_publication', $actualite->getDatePublication()->format('Y-m-d H:i:s'));
        $stmt->bindParam(':id_user', $actualite->getIdUser(), PDO::PARAM_INT);
        $stmt->execute();
    }

    public function update(Actualite $actualite): void {
        $stmt = $this->pdo->prepare('UPDATE "Actualite" SET titre_article = :titre_article, contenu = :contenu, date_publication = :date_publication WHERE id_actu = :id_actu');
        $stmt->bindParam(':titre_article', $actualite->getTitreArticle());
        $stmt->bindParam(':contenu', $actualite->getContenu());
        $stmt->bindParam(':date_publication', $actualite->getDatePublication()->format('Y-m-d H:i:s'));
        $stmt->bindParam(':id_actu', $actualite->getIdActu(), PDO::PARAM_INT);
        $stmt->execute();
    }

    public function findById(int $id): ?Actualite {
        $stmt = $this->pdo->prepare('SELECT * FROM "Actualite" WHERE id_actu = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->createActualiteFromRow($row) : null;
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare('DELETE FROM "Actualite" WHERE id_actu = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}