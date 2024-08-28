<?php

namespace App\Entity;

use App\Repository\IesireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IesireRepository::class)]
class Iesire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $data = null;

    #[ORM\Column(length: 255)]
    private ?string $nr_doc_iesire = null;

    #[ORM\ManyToOne(inversedBy: 'iesire')]
    #[ORM\JoinColumn(nullable: false)]
    private ?produs $produs = null;

    #[ORM\Column]
    private ?int $stoc_iesire = null;

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

    public function getNrDocIesire(): ?string
    {
        return $this->nr_doc_iesire;
    }

    public function setNrDocIesire(string $nr_doc_iesire): static
    {
        $this->nr_doc_iesire = $nr_doc_iesire;

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

    public function getStocIesire(): ?int
    {
        return $this->stoc_iesire;
    }

    public function setStocIesire(int $stoc_iesire): static
    {
        $this->stoc_iesire = $stoc_iesire;

        return $this;
    }
}
