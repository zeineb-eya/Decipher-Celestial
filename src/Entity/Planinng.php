<?php

namespace App\Entity;
use App\Repository\LocalisationRepository;

use App\Repository\PlaninngRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Localisation;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=PlaninngRepository::class)
 */
class Planinng
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("Planinng:read")
     */
    private $id;

    /**
     *  * @Assert\NotBlank(message="Do not leave empty"),
     * @Assert\Length(
     * min = 6,
     * max = 20,
     * minMessage = "Le nom_planning doit comporter au moins {{ limit }} caractères",
     * maxMessage = "Le nom_planning doit comporter au plus {{ limit }} caractères"
     * )
     * @ORM\Column(type="string", length=255)
     * @Groups("Planinng:read")

     */
    private $nom_planning;

    /**
     * @ORM\Column(type="date")
      * @Assert\Date()

     * @Groups("Planinng:read")
  
     */
    private $dateDebut_planning;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date()
     * @Groups("Planinng:read")
     */
    private $dateFin_planning;

    /**
    
     * @ORM\Column(type="string", length=255)
     * @Groups("Planinng:read")
     
     */
    private $destination_planning;

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload){
        if ($this->dateDebut_planning > $this->dateFin_planning){
            $context->buildViolation('start date must be earlier than end date')
            ->atPath('dateDebut_planning')
            ->addViolation();
        }
    }

    /**
     * @ORM\Column(type="text")
     * @Groups("Planinng:read")
     */
    
    private $description_planning;

    /**
     * @ORM\Column(type="float")
     * @Groups("Planinng:read")
     */
    private $periode_planning;

    /**
     * @ORM\Column(type="float")
     * @Groups("Planinng:read")
     */
    private $prix_planning;

   
    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Assert\NotBlank(message="Please upload image")
     * @Assert\File(mimeTypes={"image/jpeg"})
     */
    private $img_planinng;

     


    public function __construct()
    {
        $this->billets = new ArrayCollection();
        $this->localisation = new ArrayCollection();

    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPlanning(): ?string
    {
        return $this->nom_planning;
    }

    public function setNomPlanning(string $nom_planning): self
    {
        $this->nom_planning = $nom_planning;

        return $this;
    }

    public function getDateDebutPlanning(): ?\DateTimeInterface
    {
        return $this->dateDebut_planning;
    }

    public function setDateDebutPlanning(\DateTimeInterface $dateDebut_planning): self
    {
        $this->dateDebut_planning = $dateDebut_planning;

        return $this;
    }

    public function getDateFinPlanning(): ?\DateTimeInterface
    {
        return $this->dateFin_planning;
    }
    public function setDateFinPlanning(\DateTimeInterface $dateFin_planning): self
    {
        $this->dateFin_planning = $dateFin_planning;

        return $this;
    }

    public function getDestinationPlanning(): ?string
    {
        return $this->destination_planning;
    }

    public function setDestinationPlanning(string $destination_planning): self
    {
        $this->destination_planning = $destination_planning;

        return $this;
    }


    public function getDescriptionPlanning(): ?string
    {
        return $this->description_planning;
    }

    public function setDescriptionPlanning(string $description_planning): self
    {
        $this->description_planning = $description_planning;

        return $this;
    }

    public function getPeriodePlanning(): ?float
    {
        return $this->periode_planning;
    }

    public function setPeriodePlanning(float $periode_planning): self
    {
        $this->periode_planning = $periode_planning;

        return $this;
    }

    public function getPrixPlanning(): ?float
    {
        return $this->prix_planning;
    }

    public function setPrixPlanning(float $prix_planning): self
    {
        $this->prix_planning = $prix_planning;

        return $this;
    }


    public function getimgPlaninng()
    {
        return $this->img_planinng;
        
    }

    public function setimgPlaninng($img_planinng)
    {
        $this->img_planinng = $img_planinng;
        return $this;
    }


    

}
