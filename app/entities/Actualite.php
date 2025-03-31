<?php

Class Actualite
{

	public function __construct(
		private int $id_article,
		private string $titre_article,
		private string $contenu,
		private DateTime $date_publication,
		private int $id_user
	)
	{}

		public function setIdArticle(int $id_article): void
		{
			$this->id_article = $id_article;
		}

		public function setIdUser(int $id_user): void
		{
			$this->id_user = $id_user;
		}

	public function getIdArticle(): int
	{
		return $this->id_article;
	}


	public function getTitreArticle(): string
	{
		return $this->titre_article;
	}

	public function setTitreArticle(string $titre_article): void
	{
		$this->titre_article = $titre_article;
	}

	public function getContenu(): string
	{
		return $this->contenu;
	}

	public function setContenu(string $contenu): void
	{
		$this->contenu = $contenu;
	}

	public function getDatePublication(): DateTime
	{
		return $this->date_publication;
	}

	public function setDatePublication(DateTime $date_publication): void
	{
		$this->date_publication = $date_publication;
	}

	public function getIdUser(): int
	{
		return $this->id_user;
	}

}

?>