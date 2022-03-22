<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("reservation:read")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups("reservation:read")
     */
    private $date_reservation;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Billet::class, inversedBy="reservation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $billet;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("reservation:read")
     */
    private $Etat_reservation;
   
 
    
    
    public function __construct()
    {
    $this->date_reservation = new \DateTime('now');
    $this->Etat_reservation = 'waiting for a confirmation';
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->date_reservation;
    }

    public function setDateReservation(\DateTimeInterface $date_reservation): self
    {
        $this->date_reservation = $date_reservation;

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

    public function getBillet(): ?Billet
    {
        return $this->billet;
    }

    public function setBillet(?Billet $billet): self
    {
        $this->billet = $billet;

        return $this;
    }

    public function getEtatReservation(): ?string
    {
        return $this->Etat_reservation;
    }

    public function setEtatReservation(string $Etat_reservation): self
    {
        $this->Etat_reservation = $Etat_reservation;

        return $this;
    }


}
