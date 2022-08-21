<?php

namespace App\Entity;

use App\Repository\CandidatsAnnoncesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidatsAnnoncesRepository::class)]
class CandidatsAnnonces
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $validé = null;

    #[ORM\ManyToOne(inversedBy: 'Postuler')]
    private ?ProfilCandidat $profilCandidat = null;

    #[ORM\ManyToOne(inversedBy: 'candidats')]
    private ?Annonces $annonces = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isValidé(): ?bool
    {
        return $this->validé;
    }

    public function setValidé(bool $validé): self
    {
        $this->validé = $validé;

        return $this;
    }

    public function getProfilCandidat(): ?ProfilCandidat
    {
        return $this->profilCandidat;
    }

    public function setProfilCandidat(?ProfilCandidat $profilCandidat): self
    {
        $this->profilCandidat = $profilCandidat;

        return $this;
    }

    public function getAnnonces(): ?Annonces
    {
        return $this->annonces;
    }

    public function setAnnonces(?Annonces $annonces): self
    {
        $this->annonces = $annonces;

        return $this;
    }
}
