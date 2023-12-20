<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EncuestaPregunta
 *
 * @ORM\Table(name="encuesta_pregunta")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EncuestaPreguntaRepository")
 */
class EncuestaPregunta
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
     * @ORM\ManyToOne(targetEntity="Encuesta", inversedBy="preguntas")
     */
    private $encuesta;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Pregunta", inversedBy="preguntas")
     */
    private $pregunta;

    /**
     * @var int
     *
     * @ORM\Column(name="respuesta", type="integer")
     */
    private $respuesta;

    /**
     * @var string
     *
     * @ORM\Column(name="calificador", type="string", length=10, nullable=true)
     */
    private $calificador;

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
     * Set respuesta
     *
     * @param integer $respuesta
     *
     * @return EncuestaPregunta
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;

        return $this;
    }

    /**
     * Get respuesta
     *
     * @return integer
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    /**
     * Set calificador
     *
     * @param string $calificador
     *
     * @return EncuestaPregunta
     */
    public function setCalificador($calificador)
    {
        $this->calificador = $calificador;

        return $this;
    }

    /**
     * Get calificador
     *
     * @return string
     */
    public function getCalificador()
    {
        return $this->calificador;
    }

    /**
     * Set encuesta
     *
     * @param Encuesta $encuesta
     *
     * @return EncuestaPregunta
     */
    public function setEncuesta(Encuesta $encuesta = null)
    {
        $this->encuesta = $encuesta;

        return $this;
    }

    /**
     * Get encuesta
     *
     * @return string
     */
    public function getEncuesta()
    {
        return $this->encuesta;
    }

    /**
     * Set pregunta
     *
     * @param Pregunta $pregunta
     *
     * @return EncuestaPregunta
     */
    public function setPregunta(Pregunta $pregunta = null)
    {
        $this->pregunta = $pregunta;

        return $this;
    }

    /**
     * Get pregunta
     *
     * @return string
     */
    public function getPregunta()
    {
        return $this->pregunta;
    }
}
