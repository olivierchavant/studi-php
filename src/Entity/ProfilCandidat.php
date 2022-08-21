<?php

namespace App\Entity;

use App\Repository\ProfilCandidatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfilCandidatRepository::class)]
class ProfilCandidat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Tel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $age = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cv = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'profilCandidat', targetEntity: CandidatsAnnonces::class)]
    private Collection $Postuler;

  

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->Postuler = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTel(): ?string
    {
        return $this->Tel;
    }

    public function setTel(string $Tel): self
    {
        $this->Tel = $Tel;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(string $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, CandidatsAnnonces>
     */
    public function getPostuler(): Collection
    {
        return $this->Postuler;
    }

    public function addPostuler(CandidatsAnnonces $postuler): self
    {
        if (!$this->Postuler->contains($postuler)) {
            $this->Postuler->add($postuler);
            $postuler->setProfilCandidat($this);
        }

        return $this;
    }

    public function removePostuler(CandidatsAnnonces $postuler): self
    {
        if ($this->Postuler->removeElement($postuler)) {
            // set the owning side to null (unless already changed)
            if ($postuler->getProfilCandidat() === $this) {
                $postuler->setProfilCandidat(null);
            }
        }

        return $this;
    }

}
