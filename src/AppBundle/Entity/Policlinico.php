<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Policlinico
 *
 * @ORM\Table(name="policlinico")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PoliclinicoRepository")
 */
class Policlinico
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
     * @ORM\ManyToOne(targetEntity="Municipio", inversedBy="policlinicos")
     */
    private $municipio;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=200)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="separador", type="string", length=1)
     */
    private $separador = '-';

    /**
     * @var int
     *
     * @ORM\Column(name="tipoUnidad", type="integer")
     */
    private $tipoUnidad = 14;

    /**
     * @var string
     *
     * @ORM\Column(name="codigoUnidad", type="string", length=4, nullable=true)
     */
    private $codigoUnidad;

    /**
     * @var string
     *
     * @ORM\Column(name="codigoUnidadFull", type="string", length=10, nullable=true)
     */
    private $codigoUnidadFull;

    /**
     * @ORM\OneToMany(targetEntity="Encuesta", mappedBy="policlinico" , cascade={"remove"})
     */
    private $encuestas;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->encuestas  = new ArrayCollection();
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
     * Set separador
     *
     * @param string $separador
     *
     * @return Policlinico
     */
    public function setSeparador($separador)
    {
        $this->separador = $separador;

        return $this;
    }

    /**
     * Get separador
     *
     * @return string
     */
    public function getSeparador()
    {
        return $this->separador;
    }

    /**
     * Set tipoUnidad
     *
     * @param integer $tipoUnidad
     *
     * @return Policlinico
     */
    public function setTipoUnidad($tipoUnidad)
    {
        $this->tipoUnidad = $tipoUnidad;

        return $this;
    }

    /**
     * Get tipoUnidad
     *
     * @return int
     */
    public function getTipoUnidad()
    {
        return $this->tipoUnidad;
    }

    /**
     * Set codigoUnidad
     *
     * @param string $codigoUnidad
     *
     * @return Policlinico
     */
    public function setCodigoUnidad($codigoUnidad)
    {
        $this->codigoUnidad = $codigoUnidad;

        return $this;
    }

    /**
     * Get codigoUnidad
     *
     * @return string
     */
    public function getCodigoUnidad()
    {
        return $this->codigoUnidad;
    }

    /**
     * Set codigoUnidadFull
     *
     * @param string $codigoUnidadFull
     *
     * @return Policlinico
     */
    public function setCodigoUnidadFull($codigoUnidadFull)
    {
        $this->codigoUnidadFull = $codigoUnidadFull;

        return $this;
    }

    /**
     * Get codigoUnidadFull
     *
     * @return string
     */
    public function getCodigoUnidadFull()
    {
        return $this->codigoUnidadFull;
    }

    /**
     * Set municipio
     *
     * @param \AppBundle\Entity\Municipio $municipio
     *
     * @return Policlinico
     */
    public function setMunicipio(\AppBundle\Entity\Municipio $municipio = null)
    {
        $this->municipio = $municipio;

        return $this;
    }

    /**
     * Get municipio
     *
     * @return \AppBundle\Entity\Municipio
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Policlinico
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
     * @param \AppBundle\Entity\Encuesta $encuesta
     *
     * @return Policlinico
     */
    public function addEncuesta(\AppBundle\Entity\Encuesta $encuesta)
    {
        $this->encuestas[] = $encuesta;

        return $this;
    }

    /**
     * Remove encuesta
     *
     * @param \AppBundle\Entity\Encuesta $encuesta
     */
    public function removeEncuesta(\AppBundle\Entity\Encuesta $encuesta)
    {
        $this->encuestas->removeElement($encuesta);
    }

    /**
     * Get encuestas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuestas()
    {
        return $this->encuestas;
    }
}
