<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups("post:read")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $img_post;

    /**
     * @ORM\Column(type="text")
     * @Groups("post:read")
     */
    private $description_post;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     * @ORM\JoinColumn(onDelete="CASCADE")
     * 
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="post", orphanRemoval=true)
     */
    private $commentaires;

    /**
     * @ORM\ManyToOne(targetEntity=CategoriePost::class, inversedBy="posts" , cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $categoriePost;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getImgPost(): ?string
    {
        return $this->img_post;
    }

    public function setImgPost(string $img_post): self
    {
        $this->img_post = $img_post;

        return $this;
    }

    public function getDescriptionPost(): ?string
    {
        return $this->description_post;
    }

    public function setDescriptionPost(string $description_post): self
    {
        $this->description_post = $description_post;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setPost($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPost() === $this) {
                $commentaire->setPost(null);
            }
        }

        return $this;
    }

    public function getCategoriePost(): ?CategoriePost
    {
        return $this->categoriePost;
    }

    public function setCategoriePost(?CategoriePost $categoriePost): self
    {
        $this->categoriePost = $categoriePost;

        return $this;
    }
    public function __toString(){
        return $this->nom;
    }
}
