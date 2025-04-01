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

	public function getStatut(): string
	{
		if($this->isAdmin()) {
			return "Administrateur";
		} elseif($this->isAdherent()) {
			return "Adhérent";
		} elseif($this->isMembre()) {
			return "Membre";
		} else {
			return "Visiteur";
		}
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

	public function isMembre(): bool
	{
		return $this->isFlag(FLAG_MEMBRE);
	}

	public function isAdherent(): bool
	{
		return $this->isFlag(FLAG_ADHERENT);
	}

	public function isAdmin(): bool
	{
		return $this->isFlag(FLAG_ADMIN);
	}

	private function isFlag(int $flag): bool
	{
		return ($this->permission & $flag) !== 0;
	}

	public function addPermission($typePerm): void {
		switch ($typePerm) {
			case IS_MEMBRE:
				if (!$this->isMembre()) {
					$this->permission |= FLAG_MEMBRE;
				}
				break;
			case IS_ADHERENT:
				if (!$this->isAdherent()) {
					$this->permission |= FLAG_ADHERENT;
				}
				break;
			case IS_ADMIN:
				if (!$this->isAdmin()) {
					$this->permission |= FLAG_ADMIN;
				}
				break;
			default:
				throw new Exception("Permission non reconnue");
		}
	}

	public function removePermission($typePerm): void {
		switch ($typePerm) {
			case IS_MEMBRE:
				if ($this->isMembre()) {
					$this->permission &= ~FLAG_MEMBRE;
				}
				break;
			case IS_ADHERENT:
				if ($this->isAdherent()) {
					$this->permission &= ~FLAG_ADHERENT;
				}
				break;
			case IS_ADMIN:
				if ($this->isAdmin()) {
					$this->permission &= ~FLAG_ADMIN;
				}
				break;
			default:
				throw new Exception("Permission non reconnue");
		}
	}
}

?>
