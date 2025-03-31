<?php

const FLAG_MEMBRE = 0b00000001; // 1
const FLAG_ADHERENT = 0b00000010; // 2
const FLAG_ADMIN = 0b00000100; // 4

const IS_MEMBRE = 1;
const IS_ADHERENT = 2;
const IS_ADMIN = 4;

class Utilisateur
{
	public function __construct(
		private ?int $id_user,
		private string $mail,
		private string $mdp,
		private int $permission,
		private string $nom,
		private string $prenom
	) {}

	public function getId(): ?int
	{
		return $this->id_user;
	}

	public function getMail(): string
	{
		return $this->mail;
	}

	public function getMdp(): string
	{
		return $this->mdp;
	}

	public function getPermission(): int
	{
		return $this->permission;
	}

	public function getNom(): string
	{
		return $this->nom;
	}

	public function getPrenom(): string
	{
		return $this->prenom;
	}

	public function setId(?int $id_user): void
	{
		$this->id_user = $id_user;
	}

	public function setMail(string $mail): void
	{
		$this->mail = $mail;
	}

	public function setMdp(string $mdp): void
	{
		$this->mdp = $mdp;
	}

	public function setPermission(int $permission): void
	{
		$this->permission = $permission;
	}

	public function setNom(string $nom): void
	{
		$this->nom = $nom;
	}

	public function setPrenom(string $prenom): void
	{
		$this->prenom = $prenom;
	}

	function is_membre($value) {
		return is_flag($value, FLAG_MEMBRE);
	}
	
	function is_adherent($value) {
	  return is_flag($value, FLAG_ADHERENT);
	}
	
	function is_admin($value) {
	  return is_flag($value, FLAG_ADMIN);
	}

	function is_flag($value, $flag) {
		$etat = false;
		if ($value & $flag) $etat = true;
		return $etat;
	}

	function addPermission($typePerm) {
		switch ($typePerm) {
			case IS_MEMBRE:
				$this->permission |= FLAG_MEMBRE;
				break;
			case IS_ADHERENT:
				$this->permission |= FLAG_ADHERENT;
				break;
			case IS_ADMIN:
				$this->permission |= FLAG_ADMIN;
				break;
			default:
				throw new Exception("Permission non reconnue");
		}
	}

	function removePermission($typePerm) {
		switch ($typePerm) {
			case IS_MEMBRE:
				$this->permission &= ~FLAG_MEMBRE;
				break;
			case IS_ADHERENT:
				$this->permission &= ~FLAG_ADHERENT;
				break;
			case IS_ADMIN:
				$this->permission &= ~FLAG_ADMIN;
				break;
			default:
				throw new Exception("Permission non reconnue");
		}
	}
}

?>
