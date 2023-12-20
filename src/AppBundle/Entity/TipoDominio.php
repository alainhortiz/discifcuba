<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TipoDominio
 *
 * @ORM\Table(name="tipo_dominio")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TipoDominioRepository")
 */
class TipoDominio
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
     * @ORM\Column(name="codigo", type="string", length=3, unique=true)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100, unique=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Pregunta", mappedBy="tipoDominio" , cascade={"remove"})
     */
    private $preguntas;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->preguntas  = new ArrayCollection();
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
     * @return TipoDominio
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
     * @return TipoDominio
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
     * Add pregunta
     *
     * @param Pregunta $pregunta
     *
     * @return TipoDominio
     */
    public function addPregunta(Pregunta $pregunta)
    {
        $this->preguntas[] = $pregunta;

        return $this;
    }

    /**
     * Remove pregunta
     *
     * @param Pregunta $pregunta
     */
    public function removePregunta(Pregunta $pregunta)
    {
        $this->preguntas->removeElement($pregunta);
    }

    /**
     * Get preguntas
     *
     * @return Collection
     */
    public function getPreguntas()
    {
        return $this->preguntas;
    }
}
