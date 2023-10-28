<?php

namespace App\Entity;

use App\Repository\ReaderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReaderRepository::class)]
class Reader
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    private ?string $username = null;

   // #[ORM\ManyToMany(targetEntity: book::class, inversedBy: 'readersbooks')]
   // #[ORM\JoinTable(name:'reader_book')]
   // #[ORM\JoinColumn(name:'Book_ref', referencedColumnName:'ref')]
   // #[ORM\InverseJoinColumn(name:'reader_id', referencedColumnName: 'id')]
    private Collection $books;
    

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    
}
