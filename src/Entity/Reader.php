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

    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'readersbooks')]
    #[ORM\JoinTable(name:'reader_book')]
    #[ORM\JoinColumn(name:'reader_id', referencedColumnName:'id')]
    #[ORM\InverseJoinColumn(name:'Book_ref', referencedColumnName: 'ref')]
    private Collection $readers;

    public function __construct()
    {
        $this->readers = new ArrayCollection();
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

    /**
     * @return Collection<int, Book>
     */
    public function getReaders(): Collection
    {
        return $this->readers;
    }

    public function addReader(Book $reader): static
    {
        if (!$this->readers->contains($reader)) {
            $this->readers->add($reader);
        }

        return $this;
    }

    public function removeReader(Book $reader): static
    {
        $this->readers->removeElement($reader);

        return $this;
    }

   

   


    
}
