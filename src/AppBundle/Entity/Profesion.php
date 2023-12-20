<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Profesion
 *
 * @ORM\Table(name="profesion")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProfesionRepository")
 */
class Profesion
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
     * @ORM\Column(name="nombre", type="string", length=150, nullable=false, unique=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Usuario", mappedBy="profesion", cascade={"remove"})
     */
    private $usuarios;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->usuarios  = new ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Profesion
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
     * Add usuario
     *
     * @param Usuario $usuario
     *
     * @return Profesion
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
}
