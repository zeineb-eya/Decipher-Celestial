<?php

namespace App\Entity;
use App\Entity\Planinng;
use App\Repository\OffreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=OffreRepository::class)
 */
class Offre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
   
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Do not leave empty"),
     * @Assert\Length(
     * min = 5,
     * max = 17,
     * minMessage = "Le nom_offre doit comporter au moins {{ limit }} caractères",
     * maxMessage = "Le nom_offre doit comporter au plus {{ limit }} caractères"
     * )
     */
    private $nom_offre;
    
  /*  public function __toString() : string {
        return $this->nom_offre;
    }*/
   

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Do not leave empty"),
     * @Assert\Length(
     * min = 10,
     * max = 100,
     * minMessage = "Le description_offre doit comporter au moins {{ limit }} caractères",
     * maxMessage = "Le description_offre doit comporter au plus {{ limit }} caractères"
     * )
     */
    private $description_offre;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Do not leave empty"),
     * @Assert\Type(
     *     type="Float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\Positive
     */
    private $prix_offre;

    
    /**
     * @ORM\Column(type="float", nullable=true)
     * 
     * @Assert\Positive
     */

    private $reduction;



    /**
     * @ORM\Column(name="date_debut_offre", type="date", nullable=true)
     * @Assert\Date()
     * @Assert\GreaterThanOrEqual("Today")
     * @Assert\LessThan("+364 days")
     * Assert\NotNull()
     *Assert\NotBlank()
     
     */


    private $date_debut_offre;

   

    /**
     * @ORM\Column(name="date_fin_offre", type="date", nullable=true)
     * @Assert\Date()
     * @Assert\GreaterThan("Yesterday")
     * @Assert\LessThan("+364 days")
     
     *Assert\NotBlank()
     */
    private $date_fin_offre;

    /**
     * @ORM\ManyToOne(targetEntity=Planinng::class, inversedBy="offres")
     */
    private $planning;

    /**
    * @Assert\Callback
    */
    public function validate(ExecutionContextInterface $context, $payload) {
    if ($this->date_debut_offre > $this->date_fin_offre) {
        $context->buildViolation('Start date must be earlier than end date')
            ->atPath('date_debut_offre')
            ->addViolation();
    }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomOffre(): ?string
    {
        return $this->nom_offre;
    }

    public function setNomOffre(string $nom_offre): self
    {
        $this->nom_offre = $nom_offre;

        return $this;
    }

    public function getDescriptionOffre(): ?string
    {
        return $this->description_offre;
    }

    public function setDescriptionOffre(string $description_offre): self
    {
        $this->description_offre = $description_offre;

        return $this;
    }

    public function getPrixOffre(): ?float
    {
        return $this->prix_offre;
    }

    public function setPrixOffre(float $prix_offre): self
    {
        $this->prix_offre = $prix_offre;

        return $this;
    }

    

    public function getReduction(): ?float
    {
        return $this->reduction;
    }

    public function setReduction(float $reduction): self
    {
        $this->reduction = $reduction;

        return $this;
    }
    



    public function getDateDebutOffre(): ?\DateTimeInterface
    {
        return $this->date_debut_offre;
    }

    public function setDateDebutOffre(?\DateTimeInterface $date_debut_offre): self
    {
        $this->date_debut_offre = $date_debut_offre;

        return $this;
    }

   

    public function getDateFinOffre(): ?\DateTimeInterface
    {
        return $this->date_fin_offre;
    }

    public function setDateFinOffre(?\DateTimeInterface $date_fin_offre): self
    {
        $this->date_fin_offre = $date_fin_offre;

        return $this;
    }

    public function getPlanning(): ?Planinng
    {
        return $this->planning;
    }

    public function setPlanning(?Planinng $planning): self
    {
        $this->planning = $planning;

        return $this;
    }
   
}
