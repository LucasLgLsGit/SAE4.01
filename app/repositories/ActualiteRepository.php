<?php
require_once './app/core/Repository.php';
require_once './app/entities/Actualite.php';

class ActualiteRepository
{
	private $pdo;

	public function __construct()
	{
		$this->pdo = Repository::getInstance()->getPDO();
	}

	public function findAll(): array
	{
		$stmt = $this->pdo->query('SELECT * FROM "actualite"');
		$actualites = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$actualites[] = $this->createActualiteFromRow($row);
		}
		return $actualites;
	}

	public function create(array $data): Actualite
	{
		$errors = [];

		if (empty($data['titre_article']))
		{
			$errors[] = "Le titre de l'article est requis !";
		}
		if (empty($data['contenu']))
		{
			$errors[] = "Le contenu de l'article est requis !";
		}
		if (empty($data['id_user']))
		{
			$errors[] = "L'identifiant utilisateur (id_user) est requis !";
		}

		if (!empty($errors))
		{
			throw new Exception(implode(', ', $errors));
		}

		$actualite = new Actualite(
			null,
			$data['titre_article'],
			$data['contenu'],
			new DateTime(),
			(int) $data['id_user']
		);

		$stmt = $this->pdo->prepare('INSERT INTO "actualite" (titre_article, contenu, date_publication, id_user) VALUES (:titre_article, :contenu, :date_publication, :id_user)');
		$stmt->execute([
			':titre_article' => $actualite->getTitreArticle(),
			':contenu' => $actualite->getContenu(),
			':date_publication' => $actualite->getDatePublication()->format('Y-m-d H:i:s'),
			':id_user' => $actualite->getIdUser()
		]);

		$actualite->setIdArticle($this->pdo->lastInsertId());
		return $actualite;
	}

	private function createActualiteFromRow(array $row): Actualite
	{
		return new Actualite(
			$row['id_article'],
			$row['titre_article'],
			$row['contenu'],
			new DateTime($row['date_publication']),
			$row['id_user']
		);
	}





	public function findById(int $id): ?Actualite
	{
		$stmt = $this->pdo->prepare('SELECT * FROM "actualite" WHERE id_article = :id');
		$stmt->execute([':id' => $id]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row ? $this->createActualiteFromRow($row) : null;
	}

	public function updateById(int $id, array $data): void
	{
		$stmt = $this->pdo->prepare('UPDATE actualite SET titre_article = :titre, contenu = :contenu WHERE id_article = :id_article');
		$stmt->execute([
			'titre' => $data['titre'],
			'contenu' => $data['contenu'],
			'id_article' => $id,
		]);
	}

	public function deleteById(int $id): void
	{
		$stmt = $this->pdo->prepare('DELETE FROM actualite WHERE id_article = :id_article');
		$stmt->execute(['id_article' => $id]);
	}





	public function findLastActualites(int $limit = 10): array
	{
		$stmt = $this->pdo->prepare('
			SELECT * FROM "actualite"
			ORDER BY date_publication DESC
			LIMIT :limit
		');
		$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
		$stmt->execute();

		$actualites = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$actualites[] = $this->createActualiteFromRow($row);
		}
		return $actualites;
	}
}