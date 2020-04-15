<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\PoliticienRepository")
 * @UniqueEntity("nom",message="Politicien déjà existant avec ce nom")
 */
class Politicien
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
     * @ORM\Column(type="string", length=10)
     * @Assert\Regex(pattern="/^M|F$/", message="Sexe doit être M ou F")
     */
    private $sexe;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(value = 18, message="Age doit être => 18")
     */
    private $age;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Mairie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mairie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Parti", inversedBy="politiciens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parti;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Affaire", mappedBy="politiciens_impliques", cascade="persist")
     */
    private $affaires_impliques;

    public function __construct()
    {
        $this->affaires_impliques = new ArrayCollection();
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

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function __toString()
    {
        return $this->getNom();
    }

    public function getMairie(): ?Mairie
    {
        return $this->mairie;
    }

    public function setMairie(?Mairie $mairie): self
    {
        $this->mairie = $mairie;

        return $this;
    }

    public function getParti(): ?Parti
    {
        return $this->parti;
    }

    public function setParti(?Parti $parti): self
    {
        $this->parti = $parti;

        return $this;
    }

    /**
     * @return Collection|Affaire[]
     */
    public function getAffairesImpliques(): Collection
    {
        return $this->affaires_impliques;
    }

    public function addAffairesImplique(Affaire $affairesImplique): self
    {
        if (!$this->affaires_impliques->contains($affairesImplique)) {
            $this->affaires_impliques[] = $affairesImplique;
            $affairesImplique->addPoliticiensImplique($this);
        }

        return $this;
    }

    public function removeAffairesImplique(Affaire $affairesImplique): self
    {
        if ($this->affaires_impliques->contains($affairesImplique)) {
            $this->affaires_impliques->removeElement($affairesImplique);
            $affairesImplique->removePoliticiensImplique($this);
        }

        return $this;
    }
}
