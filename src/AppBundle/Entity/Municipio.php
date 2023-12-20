<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * Municipio
 *
 * @ORM\Table(name="municipio")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MunicipioRepository")
 */
class Municipio
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
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Provincia", inversedBy="municipios")
     */
    private $provincia;

    /**
     * @ORM\OneToMany(targetEntity="Encuesta", mappedBy="muncipioResidencia")
     *
     */
    private $encuestas;

    /**
     * @ORM\OneToMany(targetEntity="Policlinico", mappedBy="municipio" , cascade={"remove"})
     * @OrderBy({"id" = "ASC"})
     */
    private $policlinicos;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->encuestas = new ArrayCollection();
        $this->policlinicos = new ArrayCollection();
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
     * @return Municipio
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
     * @return Municipio
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
     * Set provincia
     *
     * @param Provincia $provincia
     *
     * @return Municipio
     */
    public function setProvincia(Provincia $provincia = null)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get provincia
     *
     * @return string
     */
    public function getProvincia()
    {
        return $this->provincia;
    }


    /**
     * Add encuesta
     *
     * @param Encuesta $encuesta
     *
     * @return Municipio
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

    /**
     * Add policlinico
     *
     * @param Policlinico $policlinico
     *
     * @return Municipio
     */
    public function addPoliclinico(Policlinico $policlinico)
    {
        $this->policlinicos[] = $policlinico;

        return $this;
    }

    /**
     * Remove policlinico
     *
     * @param Policlinico $policlinico
     */
    public function removePoliclinico(Policlinico $policlinico)
    {
        $this->policlinicos->removeElement($policlinico);
    }

    /**
     * Get policlinicos
     *
     * @return Collection
     */
    public function getPoliclinicos()
    {
        return $this->policlinicos;
    }
}
