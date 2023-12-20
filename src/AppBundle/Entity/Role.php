<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoleRepository")
 */
class Role implements RoleInterface
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
     * @ORM\Column(name="nombre", type="string", length=100, unique=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Notificacion", mappedBy="rol" , cascade={"remove"})
     */
    private $notificaciones;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->notificaciones = new ArrayCollection();
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
     * @return Role
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

    public function getRole()
    {
        return $this->getNombre();
    }

    public function __toString() {
        return $this->getNombre();
    }



    /**
     * Add notificacione
     *
     * @param Notificacion $notificacione
     *
     * @return Role
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
