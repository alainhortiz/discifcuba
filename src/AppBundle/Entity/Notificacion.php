<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notificacion
 *
 * @ORM\Table(name="notificacion")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NotificacionRepository")
 */
class Notificacion
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
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="notificaciones")
     */
    private $rol;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="NivelAcceso", inversedBy="notificaciones")
     */
    private $nivel;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Encuesta", inversedBy="notificaciones")
     */
    private $encuesta;

    /**
     * @var bool
     *
     * @ORM\Column(name="estado", type="boolean")
     */
    private $estado = true;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=200)
     */
    private $descripcion;

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
     * Set estado
     *
     * @param boolean $estado
     *
     * @return Notificacion
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Notificacion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set rol
     *
     * @param Role $rol
     *
     * @return Notificacion
     */
    public function setRol(Role $rol = null)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return string
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set nivel
     *
     * @param NivelAcceso $nivel
     *
     * @return Notificacion
     */
    public function setNivel(NivelAcceso $nivel = null)
    {
        $this->nivel = $nivel;

        return $this;
    }

    /**
     * Get nivel
     *
     * @return string
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set encuesta
     *
     * @param Encuesta $encuesta
     *
     * @return Notificacion
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
}
