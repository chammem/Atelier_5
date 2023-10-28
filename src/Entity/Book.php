<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\Column]
    #ORM\GeneratedValue(strategy="NONE")
    private ?int $ref = null;

    #[ORM\Column(length: 25)]
    private ?string $title = null;

    #[ORM\Column(length: 30)]
    private ?string $category = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $pubplicationDate = null;

    #[ORM\Column]
    private ?bool $published = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    private ?Author $author = null;

    #[ORM\ManyToMany(targetEntity: Reader::class, mappedBy: 'readers')]
    private Collection $readersbooks;

    public function __construct()
    {
        $this->readersbooks = new ArrayCollection();
    }

    
  

   
   

    public function getRef(): ?int
    {
        return $this->ref;
    }

    public function setRef(int $ref): self
    {
        $this->ref = $ref;
        return $this;
    }
   
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getPubplicationDate(): ?\DateTimeInterface
    {
        return $this->pubplicationDate;
    }

    public function setPubplicationDate(\DateTimeInterface $pubplicationDate): static
    {
        $this->pubplicationDate = $pubplicationDate;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): static
    {
        $this->published = $published;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Reader>
     */
    public function getReadersbooks(): Collection
    {
        return $this->readersbooks;
    }

    public function addReadersbook(Reader $readersbook): static
    {
        if (!$this->readersbooks->contains($readersbook)) {
            $this->readersbooks->add($readersbook);
            $readersbook->addReader($this);
        }

        return $this;
    }

    public function removeReadersbook(Reader $readersbook): static
    {
        if ($this->readersbooks->removeElement($readersbook)) {
            $readersbook->removeReader($this);
        }

        return $this;
    }

  

    

   

   
 
}
