<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * EstadoCivil
 *
 * @ORM\Table(name="estado_civil")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EstadoCivilRepository")
 */
class EstadoCivil
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
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Encuesta", mappedBy="estadoCivil")
     *
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return EstadoCivil
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
     * Add encuesta
     *
     * @param Encuesta $encuesta
     *
     * @return EstadoCivil
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
