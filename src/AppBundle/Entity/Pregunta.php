<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Pregunta
 *
 * @ORM\Table(name="pregunta",uniqueConstraints={@UniqueConstraint(name="IDX__UNIQUETUPLA01", columns={"codigo", "nombre"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PreguntaRepository")
 */
class Pregunta
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
     * @ORM\Column(name="codigo", type="string", length=10, nullable=false)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=254, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="TipoDominio", inversedBy="preguntas")
     */
    private $tipoDominio;

    /**
     * @ORM\OneToMany(targetEntity="EncuestaPregunta", mappedBy="pregunta" , cascade={"remove"})
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
     * @return Pregunta
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
     * @return Pregunta
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
     * Set tipoDominio
     *
     * @param TipoDominio $tipoDominio
     *
     * @return Pregunta
     */
    public function setTipoDominio(TipoDominio $tipoDominio = null)
    {
        $this->tipoDominio = $tipoDominio;

        return $this;
    }

    /**
     * Get tipoDominio
     *
     * @return string
     */
    public function getTipoDominio()
    {
        return $this->tipoDominio;
    }

    /**
     * Add pregunta
     *
     * @param EncuestaPregunta $pregunta
     *
     * @return Pregunta
     */
    public function addPregunta(EncuestaPregunta $pregunta)
    {
        $this->preguntas[] = $pregunta;

        return $this;
    }

    /**
     * Remove pregunta
     *
     * @param EncuestaPregunta $pregunta
     */
    public function removePregunta(EncuestaPregunta $pregunta)
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
