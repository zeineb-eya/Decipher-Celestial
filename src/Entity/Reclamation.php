<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Workflow\StateMachine;

/**
 * @ORM\Entity(repositoryClass=ReclamationRepository::class)
 */
class Reclamation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("reclamation:read")
     */
    private $id;


    /**
     * @ORM\Column(type="text")
     * 
     * @Assert\NotBlank(message="Do not leave empty"),
     * @Assert\Length(
     * min = 10,
     * max = 100,
     * minMessage = "Le description_offre doit comporter au moins {{ limit }} caractères",
     * maxMessage = "Le description_offre doit comporter au plus {{ limit }} caractères"
     * )
     * @Groups("reclamation:read")
     
     */



    private $description_reclamation;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date()
     * @Assert\GreaterThan("Yesterday")
     * @Assert\LessThan("tomorrow")
     * @Groups("reclamation:read")
     
     */
    private $date_reclamation;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="Reclamation")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;



/**
     * @ORM\Column(type="string", length=255, nullable=true)
     */

     private $etat_reclamation;


    public function getId(): ?int
    {
        return $this->id;
    }




    public function getDescriptionReclamation(): ?string
    {
        return $this->description_reclamation;
    }

    public function setDescriptionReclamation(string $description_reclamation): self
    {
        $this->description_reclamation = $description_reclamation;

        return $this;
    }

    public function getDateReclamation(): ?\DateTimeInterface
    {
        return $this->date_reclamation;
    }

    public function setDateReclamation(\DateTimeInterface $date_reclamation): self
    {
        $this->date_reclamation = $date_reclamation;

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

    public function getEtatReclamation(): ?string
    {
        return $this->etat_reclamation;
    }

    public function setEtatReclamation(string $etat_reclamation): self
    {
        $this->etat_reclamation = $etat_reclamation;

        return $this;
    }
  // config/packages/workflow.php
//workflow

/*private $stateMachine;


public function someMethod(Reclamation $reclamation)
{
    $this->stateMachine->apply($reclamation, 'wait_for_review', [
        'log_comment' => 'My logging comment for the wait for review transition.',
    ]);
    // ...
}*/
}
