<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Persona
 *
 * @ORM\Table(name="persona")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonaRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Persona
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="carnetIdentidad", type="string", length=11, unique=true)
     */
    private $carnetIdentidad;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="primerApellido", type="string", length=100)
     */
    private $primerApellido;

    /**
     * @var string
     *
     * @ORM\Column(name="segundoApellido", type="string", length=100)
     */
    private $segundoApellido;

    /**
     * @var string
     *
     * @ORM\Column(name="nombreCompleto", type="string", length=254)
     */
    private $nombreCompleto;

    /**
     * @var string
     *
     * @ORM\Column(name="sexo", type="string", length=20)
     */
    private $sexo;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="fechaNacimiento", type="date")
     */
    private $fechaNacimiento;

    /**
     * @ORM\OneToMany(targetEntity="Encuesta", mappedBy="persona" , cascade={"remove"})
     */
    private $encuestas;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->encuestas = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set carnetIdentidad
     *
     * @param string $carnetIdentidad
     *
     * @return Persona
     */
    public function setCarnetIdentidad($carnetIdentidad)
    {
        $this->carnetIdentidad = $carnetIdentidad;

        return $this;
    }

    /**
     * Get carnetIdentidad
     *
     * @return string
     */
    public function getCarnetIdentidad()
    {
        return $this->carnetIdentidad;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Persona
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set primerApellido
     *
     * @param string $primerApellido
     *
     * @return Persona
     */
    public function setPrimerApellido($primerApellido)
    {
        $this->primerApellido = $primerApellido;

        return $this;
    }

    /**
     * Get primerApellido
     *
     * @return string
     */
    public function getPrimerApellido()
    {
        return $this->primerApellido;
    }

    /**
     * Set segundoApellido
     *
     * @param string $segundoApellido
     *
     * @return Persona
     */
    public function setSegundoApellido($segundoApellido)
    {
        $this->segundoApellido = $segundoApellido;

        return $this;
    }

    /**
     * Get segundoApellido
     *
     * @return string
     */
    public function getSegundoApellido()
    {
        return $this->segundoApellido;
    }

    /**
     * Get nombreCompleto
     *
     * @return string
     */
    public function nombreCompleto()
    {
        return $this->getNombre() . ' ' . $this->getPrimerApellido() . ' ' . $this->getSegundoApellido();
    }

    /**
     * Get nombreCompleto
     *
     * @return string
     */
    public function nombresCompletos()
    {
        return $this->getNombre() . ' ' . $this->getPrimerApellido() . ' ' . $this->getSegundoApellido();
    }

    /**
     * Set nombreCompleto
     *
     * @param string $nombreCompleto
     *
     * @return Persona
     */
    public function setNombreCompleto($nombreCompleto)
    {
        $this->nombreCompleto = $nombreCompleto;

        return $this;
    }

    /**
     * Get nombreCompleto
     *
     * @return string
     */
    public function getNombreCompleto()
    {
        return $this->nombreCompleto;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setNombreCompletoValue()
    {
        $this->nombreCompleto = $this->nombresCompletos();
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     *
     * @return Persona
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set fechaNacimiento
     *
     * @param DateTime $fechaNacimiento
     *
     * @return Persona
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return DateTime
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Add encuesta
     *
     * @param Encuesta $encuesta
     *
     * @return Persona
     */
    public function addEncuesta(Encuesta $encuesta)
    {
        $this->encuestas[] = $encuesta;

        return $this;
    }

    /**
     * Remove encuesta
     *
     * @param Encuesta $encuesta
     */
    public function removeEncuesta(Encuesta $encuesta)
    {
        $this->encuestas->removeElement($encuesta);
    }

    /**
     * Get encuestas
     *
     * @return Collection
     */
    public function getEncuestas()
    {
        return $this->encuestas;
    }
}
