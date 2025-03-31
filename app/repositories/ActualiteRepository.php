<?php
require_once './app/core/Repository.php';
require_once './app/entities/Actualite.php';

class ActualiteRepository {
    private $pdo;

    public function __construct() {
        $this->pdo = Repository::getInstance()->getPDO();
    }

    public function findAll(): array {
        $stmt = $this->pdo->query('SELECT * FROM "actualite"');
        $actualites = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $actualites[] = $this->createActualiteFromRow($row);
        }
        return $actualites;
    }

    private function createActualiteFromRow(array $row): Actualite {
        return new Actualite(   $row['id_article'], 
                                $row['titre_article'], 
                                $row['contenu'], 
                                new DateTime($row['date_publication']), 
                                $row['id_user']);
    }

    public function create(Actualite $actualite): void {
        $stmt = $this->pdo->prepare('INSERT INTO "actualite" (titre_article, contenu, date_publication, id_user) VALUES (:titre_article, :contenu, :date_publication, :id_user)');
        $stmt->execute([
            ':titre_article' => $actualite->getTitreArticle(),
            ':contenu' => $actualite->getContenu(),
            ':date_publication' => $actualite->getDatePublication()->format('Y-m-d H:i:s'),
            ':id_user' => $actualite->getIdUser()
        ]);
    }

    public function update(Actualite $actualite): void {
        $stmt = $this->pdo->prepare('UPDATE "actualite" SET titre_article = :titre_article, contenu = :contenu, date_publication = :date_publication WHERE id_article = :id_article');
        $stmt->execute([
            ':titre_article' => $actualite->getTitreArticle(),
            ':contenu' => $actualite->getContenu(),
            ':date_publication' => $actualite->getDatePublication()->format('Y-m-d H:i:s'),
            ':id_article' => $actualite->getIdArticle()
        ]);
    }

    public function findById(int $id): ?Actualite {
        $stmt = $this->pdo->prepare('SELECT * FROM "actualite" WHERE id_article = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->createActualiteFromRow($row) : null;
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare('DELETE FROM "actualite" WHERE id_article = :id');
        $stmt->execute([':id' => $id]);
    }
}