<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestRepository::class)]
class Test
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Test1 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTest1(): ?string
    {
        return $this->Test1;
    }

    public function setTest1(string $Test1): static
    {
        $this->Test1 = $Test1;

        return $this;
    }
}
