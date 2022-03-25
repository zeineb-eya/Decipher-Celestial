<?php

namespace App\Entity;

use App\Repository\BilletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BilletRepository::class)
 */
class Billet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("billet:read")
     */
    private $id;
 
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Do not leave empty"),
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\Positive
     * @Groups("billet:read")
     */
    private $chair_billet;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Do not leave empty"),
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\Positive
     * @Groups("billet:read")
     */
    private $voyage_num;
 

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Do not leave empty"),
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\Positive
     * @Groups("billet:read")
     */
    private $terminal;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Do not leave empty"),
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\Positive
     * @Groups("billet:read")
     */
    private $portail;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="Do not leave empty")
     * @Assert\Date()
     * @Assert\GreaterThan("today")
     * @Groups("billet:read")
     */
    private $embarquement;



    /**
     * @ORM\ManyToOne(targetEntity=Localisation::class, inversedBy="billet")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("billet:read")
     */
    private $localisation;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="billet")
     */
    private $reservation;

    public function __construct()
    {
        $this->reservation = new ArrayCollection();
        $this->embarquement = new \DateTime('Tomorrow');
    }

    public function getId(): ?int
    {
        return $this->id;
    }
 


    public function getChairBillet(): ?int
    {
        return $this->chair_billet;
    }

    public function setChairBillet(int $chair_billet): self
    {
        $this->chair_billet = $chair_billet;

        return $this;
    }

    public function getVoyageNum(): ?int
    {
        return $this->voyage_num;
    }

    public function setVoyageNum(int $voyage_num): self
    {
        $this->voyage_num = $voyage_num;

        return $this;
    }
  


    public function getTerminal(): ?int
    {
        return $this->terminal;
    }

    public function setTerminal(int $terminal): self
    {
        $this->terminal = $terminal;

        return $this;
    }

    public function getPortail(): ?int
    {
        return $this->portail;
    }

    public function setPortail(int $portail): self
    {
        $this->portail = $portail;

        return $this;
    }

    public function getEmbarquement(): ?\DateTimeInterface
    {
        return $this->embarquement;
    }

    public function setEmbarquement(\DateTimeInterface $embarquement): self
    {
        $this->embarquement = $embarquement;

        return $this;
    }



    public function getLocalisation(): ?Localisation
    {
        return $this->localisation;
    }

    public function setLocalisation(?Localisation $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservation(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation[] = $reservation;
            $reservation->setBillet($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getBillet() === $this) {
                $reservation->setBillet(null);
            }
        }

        return $this;
    }

}
