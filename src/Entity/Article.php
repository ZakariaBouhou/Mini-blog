<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Article
 *
 * @ORM\Table(name="article", indexes={@ORM\Index(name="userid", columns={"userid"})})
 * @ORM\Entity
 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="articleid", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $articleid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="title", type="string", length=64, nullable=true)
     * @Assert\NotBlank(message = "Le titre ne doit pas être vide")
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(name="content", type="text", length=0, nullable=true)
     * @Assert\NotBlank(message = "Le message ne doit pas être vide")
     */
    private $content;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userid", referencedColumnName="userid")
     * })
     */
    private $userid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="articleid")
     * @ORM\JoinTable(name="belong_to",
     *   joinColumns={
     *     @ORM\JoinColumn(name="articleid", referencedColumnName="articleid")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="categoryid", referencedColumnName="categoryid")
     *   }
     * )
     */
    private $categoryid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categoryid = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getArticleid(): ?int
    {
        return $this->articleid;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUserid(): ?User
    {
        return $this->Userid;
    }

    public function setUserid(?User $userid): self
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategoryid(): Collection
    {
        return $this->categoryid;
    }

    public function addCategoryid(Category $categoryid): self
    {
        if (!$this->categoryid->contains($categoryid)) {
            $this->categoryid[] = $categoryid;
        }

        return $this;
    }

    public function removeCategoryid(Category $categoryid): self
    {
        $this->categoryid->removeElement($categoryid);

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getImage(): ?Picture
    {
        return $this->image;
    }

    public function setImage(Picture $image): self
    {
        // set the owning side of the relation if necessary
        if ($image->getArticle() !== $this) {
            $image->setArticle($this);
        }

        $this->image = $image;

        return $this;
    }

}
