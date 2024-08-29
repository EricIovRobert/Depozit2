<?php

namespace App\Entity;

use App\Repository\IntrareRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IntrareRepository::class)]
class Intrare
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $data = null;

    #[ORM\Column(length: 255)]
    private ?string $nr_doc_intrare = null;

    #[ORM\Column]
    private ?int $intrari = null;

    #[ORM\ManyToOne(inversedBy: 'intrare')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produs $produs = null;

    #[ORM\Column]
    private ?int $nefolosibile = null;

    #[ORM\Column]
    private ?int $stoc_intrare = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }

    public function setData(\DateTimeInterface $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getNrDocIntrare(): ?string
    {
        return $this->nr_doc_intrare;
    }

    public function setNrDocIntrare(string $nr_doc_intrare): static
    {
        $this->nr_doc_intrare = $nr_doc_intrare;

        return $this;
    }

    public function getIntrari(): ?int
    {
        return $this->intrari;
    }

    public function setIntrari(int $intrari): static
    {
        $this->intrari = $intrari;

        return $this;
    }

    public function getProdus(): ?produs
    {
        return $this->produs;
    }

    public function setProdus(?produs $produs): static
    {
        $this->produs = $produs;

        return $this;
    }

    public function getNefolosibile(): ?int
    {
        return $this->nefolosibile;
    }

    public function setNefolosibile(int $nefolosibile): static
    {
        $this->nefolosibile = $nefolosibile;

        return $this;
    }

    public function getStocIntrare(): ?int
    {
        return $this->stoc_intrare;
    }

    public function setStocIntrare(int $stoc_intrare): static
    {
        $this->stoc_intrare = $stoc_intrare;

        return $this;
    }
}
