<?php

namespace App\Entity;

use App\Repository\CategoriePostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=CategoriePostRepository::class)
 */
class CategoriePost
{
    /**
     * @ORM\id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("categorie:read")
     */
    private $id;

    public function __toString()
    {
        return $this->nom_categorie_post;
    }
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="le champ est vide")
     * @Groups("categorie:read")
     */
    private $nom_categorie_post;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="categoriePost", orphanRemoval=true)
     */
    private $posts;

        public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategoriePost(): ?string
    {
        return $this->nom_categorie_post;
    }

    public function setNomCategoriePost(string $nom_categorie_post): self
    {
        $this->nom_categorie_post = $nom_categorie_post;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCategoriePost($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCategoriePost() === $this) {
                $post->setCategoriePost(null);
            }
        }

        return $this;
    }

   
   
}
