<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartiRepository")
 * @UniqueEntity("nom",message="Parti déjà existant")
 */
class Parti
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Politicien", mappedBy="parti")
     * @ORM\JoinColumn(nullable=true)
     */
    private $politiciens;

    public function __construct()
    {
        $this->politiciens = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getPoliticiens()
    {
        return $this->politiciens;
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
    public function __toString()
    {
        return $this->getNom();
    }
}
