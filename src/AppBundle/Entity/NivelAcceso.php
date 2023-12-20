<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * NivelAcceso
 *
 * @ORM\Table(name="nivel_acceso")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NivelAccesoRepository")
 */
class NivelAcceso
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
     * @ORM\Column(name="nivel", type="string", length=20, unique=true)
     */
    private $nivel;

    /**
     * @ORM\OneToMany(targetEntity="Usuario", mappedBy="nivelAcceso")
     */
    private $usuarios;

    /**
     * @ORM\OneToMany(targetEntity="Notificacion", mappedBy="nivel" , cascade={"remove"})
     */
    private $notificaciones;

    /**
     * Contacto constructor.
     */
    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
        $this->notificaciones = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nivel
     *
     * @param string $nivel
     *
     * @return NivelAcceso
     */
    public function setNivel($nivel)
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
     * Add usuario
     *
     * @param Usuario $usuario
     *
     * @return NivelAcceso
     */
    public function addUsuario(Usuario $usuario)
    {
        $this->usuarios[] = $usuario;

        return $this;
    }

    /**
     * Remove usuario
     *
     * @param Usuario $usuario
     */
    public function removeUsuario(Usuario $usuario)
    {
        $this->usuarios->removeElement($usuario);
    }

    /**
     * Get usuarios
     *
     * @return Collection
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    /**
     * Add notificacione
     *
     * @param Notificacion $notificacione
     *
     * @return NivelAcceso
     */
    public function addNotificacione(Notificacion $notificacione)
    {
        $this->notificaciones[] = $notificacione;

        return $this;
    }

    /**
     * Remove notificacione
     *
     * @param Notificacion $notificacione
     */
    public function removeNotificacione(Notificacion $notificacione)
    {
        $this->notificaciones->removeElement($notificacione);
    }

    /**
     * Get notificaciones
     *
     * @return Collection
     */
    public function getNotificaciones()
    {
        return $this->notificaciones;
    }
}
