<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UsuarioRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Usuario implements AdvancedUserInterface , Serializable
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
     * @ORM\Column(name="username", type="string", length=100, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="primerApellido", type="string", length=100)
     */
    private $primerApellido;

    /**
     * @var string
     *
     * @ORM\Column(name="segundoApellido", type="string", length=100)
     */
    private $segundoApellido;

    /**
     * @var string
     *
     * @ORM\Column(name="nombreCompleto", type="string", length=254)
     */
    private $nombreCompleto;

    /**
     * @var bool
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive = true;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Profesion", inversedBy="usuarios")
     */
    private $profesion;

    /**
     * @ORM\OneToMany(targetEntity="Encuesta", mappedBy="usuarioRegistra" , cascade={"remove"})
     */
    private $encuestas;

    /**
     * @ORM\OneToMany(targetEntity="Traza", mappedBy="usuario")
     */
    private $trazas;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="NivelAcceso", inversedBy="usuarios")
     */
    private $nivelAcceso;

    /**
     * @var bool
     *
     * @ORM\Column(name="supervisor", type="boolean")
     */
    private $supervisor = true;

    /**
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinTable(name="usuario_role",
     *     joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    private $usuario_roles;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->encuestas = new ArrayCollection();
        $this->usuario_roles = new ArrayCollection();
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
     * Set username
     *
     * @param string $username
     *
     * @return Usuario
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Usuario
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Usuario
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
     * Set primerApellido
     *
     * @param string $primerApellido
     *
     * @return Usuario
     */
    public function setPrimerApellido($primerApellido)
    {
        $this->primerApellido = $primerApellido;

        return $this;
    }

    /**
     * Get primerApellido
     *
     * @return string
     */
    public function getPrimerApellido()
    {
        return $this->primerApellido;
    }

    /**
     * Set segundoApellido
     *
     * @param string $segundoApellido
     *
     * @return Usuario
     */
    public function setSegundoApellido($segundoApellido)
    {
        $this->segundoApellido = $segundoApellido;

        return $this;
    }

    /**
     * Get segundoApellido
     *
     * @return string
     */
    public function getSegundoApellido()
    {
        return $this->segundoApellido;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Usuario
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Add usuarioRole
     *
     * @param Role $usuarioRole
     *
     * @return Usuario
     */
    public function addUsuarioRole(Role $usuarioRole)
    {
        $this->usuario_roles[] = $usuarioRole;

        return $this;
    }

    /**
     * Remove usuarioRole
     *
     * @param Role $usuarioRole
     */
    public function removeUsuarioRole(Role $usuarioRole)
    {
        $this->usuario_roles->removeElement($usuarioRole);
    }

    /**
     * Get usuarioRoles
     *
     * @return Collection
     */
    public function getUsuarioRoles()
    {
        return $this->usuario_roles;
    }

    // Inicio metodos para el mecanismo de seguridad
    public function setUsuarioRoles($roles)
    {
        $this->usuario_roles = $roles;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
            ) = unserialize($serialized);
    }

    public function getRoles()
    {
        //IMPORTANTE: el mecanismo de seguridad de Sf2 requiere ésto como un array

        $roles = array();
        foreach ($this->usuario_roles as $role) {
            $roles[] = $role->getRole();
        }
        return $roles;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
    // Fin metodos para el mecanismo de seguridad


    /**
     * Get nombreCompleto
     *
     * @return string
     */
    public function nombresCompletos()
    {
        return $this->getNombre() . ' ' . $this->getPrimerApellido() . ' ' . $this->getSegundoApellido();
    }

    /**
     * Set profesion
     *
     * @param Profesion $profesion
     *
     * @return Usuario
     */
    public function setProfesion(Profesion $profesion = null)
    {
        $this->profesion = $profesion;

        return $this;
    }

    /**
     * Get profesion
     *
     * @return string
     */
    public function getProfesion()
    {
        return $this->profesion;
    }

    /**
     * Set nivelAcceso
     *
     * @param NivelAcceso $nivelAcceso
     *
     * @return Usuario
     */
    public function setNivelAcceso(NivelAcceso $nivelAcceso = null)
    {
        $this->nivelAcceso = $nivelAcceso;

        return $this;
    }

    /**
     * Get nivelAcceso
     *
     * @return string
     */
    public function getNivelAcceso()
    {
        return $this->nivelAcceso;
    }

    /**
     * Add encuesta
     *
     * @param Encuesta $encuesta
     *
     * @return Usuario
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
     * Set nombreCompleto
     *
     * @param string $nombreCompleto
     *
     * @return Usuario
     */
    public function setNombreCompleto($nombreCompleto)
    {
        $this->nombreCompleto = $nombreCompleto;

        return $this;
    }

    /**
     * Get nombreCompleto
     *
     * @return string
     */
    public function getNombreCompleto()
    {
        return $this->nombreCompleto;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setNombreCompletoValue()
    {
        $this->nombreCompleto = $this->nombresCompletos();
    }


    /**
     * Add traza
     *
     * @param Traza $traza
     *
     * @return Usuario
     */
    public function addTraza(Traza $traza)
    {
        $this->trazas[] = $traza;

        return $this;
    }

    /**
     * Remove traza
     *
     * @param Traza $traza
     */
    public function removeTraza(Traza $traza)
    {
        $this->trazas->removeElement($traza);
    }

    /**
     * Get trazas
     *
     * @return Collection
     */
    public function getTrazas()
    {
        return $this->trazas;
    }

    /**
     * Set supervisor
     *
     * @param boolean $supervisor
     *
     * @return Usuario
     */
    public function setSupervisor($supervisor)
    {
        $this->supervisor = $supervisor;

        return $this;
    }

    /**
     * Get supervisor
     *
     * @return boolean
     */
    public function getSupervisor()
    {
        return $this->supervisor;
    }
}
