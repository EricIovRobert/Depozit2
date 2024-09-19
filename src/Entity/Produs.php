<?php

namespace App\Entity;

use App\Repository\ProdusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProdusRepository::class)]
class Produs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nume = null;

    #[ORM\Column]
     /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual(value=0, message="Stocul nu poate fi sub 0.")
     */
    private ?int $stoc = null;

    /**
     * @var Collection<int, Intrare>
     */
    #[ORM\OneToMany(targetEntity: Intrare::class, mappedBy: 'produs')]
    private Collection $intrare;

    /**
     * @var Collection<int, Iesire>
     */
    #[ORM\OneToMany(targetEntity: Iesire::class, mappedBy: 'produs')]
    private Collection $iesire;

    #[ORM\Column(type: 'boolean')]
    private bool $available = true;

    #[ORM\Column]
    private ?int $total = null;

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function __construct()
    {
        $this->intrare = new ArrayCollection();
        $this->iesire = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNume(): ?string
    {
        return $this->nume;
    }

    public function setNume(string $nume): static
    {
        $this->nume = $nume;

        return $this;
    }

    public function getStoc(): ?int
    {
        return $this->stoc;
    }

    public function setStoc(int $stoc): static
    {
        $this->stoc = $stoc;

        return $this;
    }

    /**
     * @return Collection<int, Intrare>
     */
    public function getIntrare(): Collection
    {
        return $this->intrare;
    }

    public function addIntrare(Intrare $intrare): static
    {
        if (!$this->intrare->contains($intrare)) {
            $this->intrare->add($intrare);
            $intrare->setProdus($this);
        }

        return $this;
    }

    public function removeIntrare(Intrare $intrare): static
    {
        if ($this->intrare->removeElement($intrare)) {
            // set the owning side to null (unless already changed)
            if ($intrare->getProdus() === $this) {
                $intrare->setProdus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Iesire>
     */
    public function getIesire(): Collection
    {
        return $this->iesire;
    }

    public function addIesire(Iesire $iesire): static
    {
        if (!$this->iesire->contains($iesire)) {
            $this->iesire->add($iesire);
            $iesire->setProdus($this);
        }

        return $this;
    }

    public function removeIesire(Iesire $iesire): static
    {
        if ($this->iesire->removeElement($iesire)) {
            // set the owning side to null (unless already changed)
            if ($iesire->getProdus() === $this) {
                $iesire->setProdus(null);
            }
        }

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): static
    {
        $this->total = $total;

        return $this;
    }
}
