<?php
require_once './app/core/Repository.php';
require_once './app/entities/Product.php';

class ProductRepository {
	private $pdo;

	public function __construct() {
		$this->pdo = Repository::getInstance()->getPDO();
	}

	public function findAll(): array {
		$stmt = $this->pdo->query('SELECT * FROM "Product"');
		$products = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$products[] = $this->createProductFromRow($row);
		}
		return $products;
	}

	private function createProductFromRow(array $row): Product
	{
		return new Product($row['id_produit'], $row['nom_produit'], $row['description_produit'], $row['date_ajout'], $row['couleur'], $row['taille'], $row['stock'], $row['prix'], $row['id_user']);
	}

	public function create(User $user): bool {
		
	}
}