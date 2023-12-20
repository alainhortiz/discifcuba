<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * Provincia
 *
 * @ORM\Table(name="provincia")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProvinciaRepository")
 */
class Provincia
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
     * @ORM\Column(name="codigo", type="string", length=20, unique=true)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Municipio", mappedBy="provincia" , cascade={"remove"})
     * @OrderBy({"id" = "ASC"})
     */
    private $municipios;

    /**
     * Provincia constructor.
     */
    public function __construct()
    {
        $this->municipios = new ArrayCollection();
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
     * Set codigo
     *
     * @param string $codigo
     *
     * @return Provincia
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Provincia
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
     * Add municipio
     *
     * @param Municipio $municipio
     *
     * @return Provincia
     */
    public function addMunicipio(Municipio $municipio)
    {
        $this->municipios[] = $municipio;

        return $this;
    }

    /**
     * Remove municipio
     *
     * @param Municipio $municipio
     */
    public function removeMunicipio(Municipio $municipio)
    {
        $this->municipios->removeElement($municipio);
    }

    /**
     * Get municipios
     *
     * @return Collection
     */
    public function getMunicipios()
    {
        return $this->municipios;
    }
}
