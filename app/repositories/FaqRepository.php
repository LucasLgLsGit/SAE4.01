<?php
require_once './app/core/Repository.php';
require_once './app/entities/Question.php';

class FaqRepository {
	private $pdo;

	public function __construct() {
		$this->pdo = Repository::getInstance()->getPDO();
	}

	public function findAll() {
		$stmt = $this->pdo->prepare('SELECT * FROM question');
		$stmt->execute();
		$faqs = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$faqs[] = $this->createQuestionFromRow($row);
		}
		return $faqs;
	}

	public function create(array $data): Question {
		$errors = [];
		if (empty($data['question'])) {
			$errors[] = "La question est requise !";
		}
		if (empty($data['reponse'])) {
			$errors[] = "La réponse est requise !";
		}
		if (!empty($errors)) {
			throw new Exception(implode(', ', $errors));
		}
		$question = new Question(
			null,
			$data['question'],
			$data['reponse']
		);
		$stmt = $this->pdo->prepare('
			INSERT INTO question (question, reponse) 
			VALUES (:question, :reponse)
		');
		$success = $stmt->execute([
			'question' => $data['question'],
			'reponse' => $data['reponse'],
		]);
		if (!$success) {
			throw new Exception("La création de la question a échoué.");
		}
		$questionId = $this->pdo->lastInsertId();
		return $question;

	}

	private function createQuestionFromRow(array $row): Question {
		return new Question(
			$row['id_question'],
			$row['question'],
			$row['reponse']
		);
	}
}