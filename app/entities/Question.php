<?php

class Question
{
	private ?int $id_question;
	private string $question;
	private string $reponse;

	public function __construct(?int $id_question, string $question, string $reponse)
	{
		$this->id_question = $id_question;
		$this->question = $question;
		$this->reponse = $reponse;
	}

	public function getId(): int
	{
		return $this->id_question;
	}

	public function getQuestion(): string
	{
		return $this->question;
	}

	public function getReponse(): string
	{
		return $this->reponse;
	}

	public function setQuestion(string $question): void
	{
		$this->question = $question;
	}

	public function setReponse(string $reponse): void
	{
		$this->reponse = $reponse;
	}
}