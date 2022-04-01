<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity
 * @UniqueEntity("name", message="Cette catégorie est déja présente")
 * 
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="categoryid", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $categoryid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=true, unique=true)
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="categoryid")
     */
    private $articleid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articleid = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getCategoryid(): ?int
    {
        return $this->categoryid;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticleid(): Collection
    {
        return $this->articleid;
    }

    public function addArticleid(Article $articleid): self
    {
        if (!$this->articleid->contains($articleid)) {
            $this->articleid[] = $articleid;
            $articleid->addCategoryid($this);
        }

        return $this;
    }

    public function removeArticleid(Article $articleid): self
    {
        if ($this->articleid->removeElement($articleid)) {
            $articleid->removeCategoryid($this);
        }

        return $this;
    }

}
