<?php

class Utilisateur
{
    public function __construct(
        private ?int $id_user,
        private string $mail,
        private string $mdp,
        private string $permission,
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

    public function getPermission(): string
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

    public function setPermission(string $permission): void
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
}

?>
