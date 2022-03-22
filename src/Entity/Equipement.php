<?php

namespace App\Entity;

use App\Repository\EquipementRepository;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipementRepository::class)
 */
class Equipement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank
      * @Assert\Type("string")
     */
    private $nom_equipement;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank
     *  @Assert\Choice(
     *     choices = {"new", "used"},
     *     message = "Choose a valid state(new,used)."
     * )
     */
    private $etat_equipement;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $description_equipement;

    /**
     * @ORM\ManyToOne(targetEntity=CategorieEquipement::class, inversedBy="Equipement")
     */
    private $categorieEquipement;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please Upload image")
     * @Assert\File(mimeTypes={"image/jpeg"})
     */
    private $image_equipement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEquipement(): ?string
    {
        return $this->nom_equipement;
    }

    public function setNomEquipement(string $nom_equipement): self
    {
        $this->nom_equipement = $nom_equipement;

        return $this;
    }

    public function getEtatEquipement(): ?string
    {
        return $this->etat_equipement;
    }

    public function setEtatEquipement(string $etat_equipement): self
    {
        $this->etat_equipement = $etat_equipement;

        return $this;
    }

    public function getDescriptionEquipement(): ?string
    {
        return $this->description_equipement;
    }

    public function setDescriptionEquipement(string $description_equipement): self
    {
        $this->description_equipement = $description_equipement;

        return $this;
    }

    public function getCategorieEquipement(): ?CategorieEquipement
    {
        return $this->categorieEquipement;
    }

    public function setCategorieEquipement(?CategorieEquipement $categorieEquipement): self
    {
        $this->categorieEquipement = $categorieEquipement;

        return $this;
    }

    public function getImageEquipement()
    {
        return $this->image_equipement;
    }

    public function setImageEquipement( $image_equipement)
    {
        $this->image_equipement = $image_equipement;

        return $this;
    }
}