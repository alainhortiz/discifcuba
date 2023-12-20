<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Encuesta
 *
 * @ORM\Table(name="encuesta")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EncuestaRepository")
 */
class Encuesta
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
     * @var DateTime
     *
     * @ORM\Column(name="fechaValoracion", type="date")
     */
    private $fechaValoracion;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Persona", inversedBy="encuestas")
     */
    private $persona;

    /**
     * @var int
     *
     * @ORM\Column(name="edad", type="integer")
     */
    private $edad;

    /**
     * @var string
     *
     * @ORM\Column(name="genero", type="string", length=20)
     */
    private $genero;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Municipio", inversedBy="encuestas")
     */
    private $muncipioResidencia;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Policlinico", inversedBy="encuestas")
     */
    private $policlinico;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="EstadoCivil", inversedBy="encuestas")
     */
    private $estadoCivil;

    /**
     * @ORM\ManyToMany(targetEntity="Empleo")
     * @ORM\JoinTable(name="encuesta_empleo",
     *     joinColumns={@ORM\JoinColumn(name="encuesta_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="empleo_id", referencedColumnName="id")}
     * )
     */
    private $encuesta_empleos;

    /**
     * @ORM\ManyToMany(targetEntity="GradoIndependencia")
     * @ORM\JoinTable(name="encuesta_grado",
     *     joinColumns={@ORM\JoinColumn(name="encuesta_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="grado_id", referencedColumnName="id")}
     * )
     */
    private $encuesta_grados;

    /**
     * @ORM\ManyToMany(targetEntity="FactorRiesgo")
     * @ORM\JoinTable(name="encuesta_riesgo",
     *     joinColumns={@ORM\JoinColumn(name="encuesta_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="riesgo_id", referencedColumnName="id")}
     * )
     */
    private $encuesta_riesgos;

    /**
     * @ORM\ManyToMany(targetEntity="DiagnosticoMedico")
     * @ORM\JoinTable(name="encuesta_diagnostico",
     *     joinColumns={@ORM\JoinColumn(name="encuesta_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="diagnostico_id", referencedColumnName="id")}
     * )
     */
    private $encuesta_diagnosticos;

    /**
     * @ORM\ManyToMany(targetEntity="SistemaAfectado")
     * @ORM\JoinTable(name="encuesta_sistema",
     *     joinColumns={@ORM\JoinColumn(name="encuesta_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="sistema_id", referencedColumnName="id")}
     * )
     */
    private $encuesta_sistemas;

    /**
     * @ORM\ManyToMany(targetEntity="FuncionAfectado")
     * @ORM\JoinTable(name="encuesta_funcion",
     *     joinColumns={@ORM\JoinColumn(name="encuesta_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="funcion_id", referencedColumnName="id")}
     * )
     */
    private $encuesta_funciones;

    /**
     * @ORM\ManyToMany(targetEntity="Usuario")
     * @ORM\JoinTable(name="encuesta_entrevistador",
     *     joinColumns={@ORM\JoinColumn(name="encuesta_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id")}
     * )
     */
    private $encuesta_entrevistadores;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="encuestas")
     */
    private $usuarioRegistra;

    /**
     * @ORM\OneToMany(targetEntity="EncuestaPregunta", mappedBy="encuesta" , cascade={"remove"})
     */
    private $preguntas;

    /**
     * @var int
     *
     * @ORM\Column(name="aprobacion", type="integer")
     */
    private $aprobacion = 1;

    /**
     * @ORM\OneToMany(targetEntity="Notificacion", mappedBy="encuesta" , cascade={"remove"})
     */
    private $notificaciones;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->encuesta_empleos = new ArrayCollection();
        $this->encuesta_grados = new ArrayCollection();
        $this->encuesta_riesgos = new ArrayCollection();
        $this->encuesta_diagnosticos = new ArrayCollection();
        $this->encuesta_sistemas = new ArrayCollection();
        $this->encuesta_funciones = new ArrayCollection();
        $this->encuesta_entrevistadores = new ArrayCollection();
        $this->preguntas = new ArrayCollection();
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
     * Set fechaValoracion
     *
     * @param DateTime $fechaValoracion
     *
     * @return Encuesta
     */
    public function setFechaValoracion($fechaValoracion)
    {
        $this->fechaValoracion = $fechaValoracion;

        return $this;
    }

    /**
     * Get fechaValoracion
     *
     * @return DateTime
     */
    public function getFechaValoracion()
    {
        return $this->fechaValoracion;
    }

    /**
     * Set edad
     *
     * @param integer $edad
     *
     * @return Encuesta
     */
    public function setEdad($edad)
    {
        $this->edad = $edad;

        return $this;
    }

    /**
     * Get edad
     *
     * @return integer
     */
    public function getEdad()
    {
        return $this->edad;
    }

    /**
     * Set muncipioResidencia
     *
     * @param Municipio $muncipioResidencia
     *
     * @return Encuesta
     */
    public function setMuncipioResidencia(Municipio $muncipioResidencia = null)
    {
        $this->muncipioResidencia = $muncipioResidencia;

        return $this;
    }

    /**
     * Get muncipioResidencia
     *
     * @return string
     */
    public function getMuncipioResidencia()
    {
        return $this->muncipioResidencia;
    }

    /**
     * Set estadoCivil
     *
     * @param EstadoCivil $estadoCivil
     *
     * @return Encuesta
     */
    public function setEstadoCivil(EstadoCivil $estadoCivil = null)
    {
        $this->estadoCivil = $estadoCivil;

        return $this;
    }

    /**
     * Get estadoCivil
     *
     * @return string
     */
    public function getEstadoCivil()
    {
        return $this->estadoCivil;
    }

    /**
     * Add encuestaEmpleo
     *
     * @param Empleo $encuestaEmpleo
     *
     * @return Encuesta
     */
    public function addEncuestaEmpleo(Empleo $encuestaEmpleo)
    {
        $this->encuesta_empleos[] = $encuestaEmpleo;

        return $this;
    }

    /**
     * Remove encuestaEmpleo
     *
     * @param Empleo $encuestaEmpleo
     */
    public function removeEncuestaEmpleo(Empleo $encuestaEmpleo)
    {
        $this->encuesta_empleos->removeElement($encuestaEmpleo);
    }

    /**
     * Get encuestaEmpleos
     *
     * @return Collection
     */
    public function getEncuestaEmpleos()
    {
        return $this->encuesta_empleos;
    }

    public function setEncuestaEmpleos($empleos)
    {
        $this->encuesta_empleos = $empleos;
    }

    /**
     * Add encuestaGrado
     *
     * @param GradoIndependencia $encuestaGrado
     *
     * @return Encuesta
     */
    public function addEncuestaGrado(GradoIndependencia $encuestaGrado)
    {
        $this->encuesta_grados[] = $encuestaGrado;

        return $this;
    }

    /**
     * Remove encuestaGrado
     *
     * @param GradoIndependencia $encuestaGrado
     */
    public function removeEncuestaGrado(GradoIndependencia $encuestaGrado)
    {
        $this->encuesta_grados->removeElement($encuestaGrado);
    }

    /**
     * Get encuestaGrados
     *
     * @return Collection
     */
    public function getEncuestaGrados()
    {
        return $this->encuesta_grados;
    }

    public function setEncuestaGrados($grados)
    {
        $this->encuesta_grados = $grados;
    }

    /**
     * Add encuestaRiesgo
     *
     * @param FactorRiesgo $encuestaRiesgo
     *
     * @return Encuesta
     */
    public function addEncuestaRiesgo(FactorRiesgo $encuestaRiesgo)
    {
        $this->encuesta_riesgos[] = $encuestaRiesgo;

        return $this;
    }

    /**
     * Remove encuestaRiesgo
     *
     * @param FactorRiesgo $encuestaRiesgo
     */
    public function removeEncuestaRiesgo(FactorRiesgo $encuestaRiesgo)
    {
        $this->encuesta_riesgos->removeElement($encuestaRiesgo);
    }

    /**
     * Get encuestaRiesgos
     *
     * @return Collection
     */
    public function getEncuestaRiesgos()
    {
        return $this->encuesta_riesgos;
    }

    public function setEncuestaRiesgos($riesgos)
    {
        $this->encuesta_riesgos = $riesgos;
    }

    /**
     * Add encuestaDiagnostico
     *
     * @param DiagnosticoMedico $encuestaDiagnostico
     *
     * @return Encuesta
     */
    public function addEncuestaDiagnostico(DiagnosticoMedico $encuestaDiagnostico)
    {
        $this->encuesta_diagnosticos[] = $encuestaDiagnostico;

        return $this;
    }

    /**
     * Remove encuestaDiagnostico
     *
     * @param DiagnosticoMedico $encuestaDiagnostico
     */
    public function removeEncuestaDiagnostico(DiagnosticoMedico $encuestaDiagnostico)
    {
        $this->encuesta_diagnosticos->removeElement($encuestaDiagnostico);
    }

    /**
     * Get encuestaDiagnosticos
     *
     * @return Collection
     */
    public function getEncuestaDiagnosticos()
    {
        return $this->encuesta_diagnosticos;
    }

    public function setEncuestaDiagnosticos($diagnosticos)
    {
        $this->encuesta_diagnosticos = $diagnosticos;
    }

    /**
     * Add encuestaSistema
     *
     * @param SistemaAfectado $encuestaSistema
     *
     * @return Encuesta
     */
    public function addEncuestaSistema(SistemaAfectado $encuestaSistema)
    {
        $this->encuesta_sistemas[] = $encuestaSistema;

        return $this;
    }

    /**
     * Remove encuestaSistema
     *
     * @param SistemaAfectado $encuestaSistema
     */
    public function removeEncuestaSistema(SistemaAfectado $encuestaSistema)
    {
        $this->encuesta_sistemas->removeElement($encuestaSistema);
    }

    /**
     * Get encuestaSistemas
     *
     * @return Collection
     */
    public function getEncuestaSistemas()
    {
        return $this->encuesta_sistemas;
    }

    public function setEncuestaSistemas($sistemas)
    {
        $this->encuesta_sistemas = $sistemas;
    }

    /**
     * Add encuestaFuncione
     *
     * @param FuncionAfectado $encuestaFuncione
     *
     * @return Encuesta
     */
    public function addEncuestaFuncione(FuncionAfectado $encuestaFuncione)
    {
        $this->encuesta_funciones[] = $encuestaFuncione;

        return $this;
    }

    /**
     * Remove encuestaFuncione
     *
     * @param FuncionAfectado $encuestaFuncione
     */
    public function removeEncuestaFuncione(FuncionAfectado $encuestaFuncione)
    {
        $this->encuesta_funciones->removeElement($encuestaFuncione);
    }

    /**
     * Get encuestaFunciones
     *
     * @return Collection
     */
    public function getEncuestaFunciones()
    {
        return $this->encuesta_funciones;
    }

    public function setEncuestaFunciones($funciones)
    {
        $this->encuesta_funciones = $funciones;
    }

    /**
     * Set usuarioRegistra
     *
     * @param Usuario $usuarioRegistra
     *
     * @return Encuesta
     */
    public function setUsuarioRegistra(Usuario $usuarioRegistra = null)
    {
        $this->usuarioRegistra = $usuarioRegistra;

        return $this;
    }

    /**
     * Get usuarioRegistra
     *
     * @return string
     */
    public function getUsuarioRegistra()
    {
        return $this->usuarioRegistra;
    }

    /**
     * Add pregunta
     *
     * @param EncuestaPregunta $pregunta
     *
     * @return Encuesta
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

    /**
     * Add encuestaEntrevistadore
     *
     * @param Usuario $encuestaEntrevistadore
     *
     * @return Encuesta
     */
    public function addEncuestaEntrevistadore(Usuario $encuestaEntrevistadore)
    {
        $this->encuesta_entrevistadores[] = $encuestaEntrevistadore;

        return $this;
    }

    /**
     * Remove encuestaEntrevistadore
     *
     * @param Usuario $encuestaEntrevistadore
     */
    public function removeEncuestaEntrevistadore(Usuario $encuestaEntrevistadore)
    {
        $this->encuesta_entrevistadores->removeElement($encuestaEntrevistadore);
    }

    /**
     * Get encuestaEntrevistadores
     *
     * @return Collection
     */
    public function getEncuestaEntrevistadores()
    {
        return $this->encuesta_entrevistadores;
    }

    public function setEncuestaEntrevistadores($entrevistadores)
    {
        $this->encuesta_entrevistadores = $entrevistadores;
    }

    /**
     * Set aprobacion
     *
     * @param integer $aprobacion
     *
     * @return Encuesta
     */
    public function setAprobacion($aprobacion)
    {
        $this->aprobacion = $aprobacion;

        return $this;
    }

    /**
     * Get aprobacion
     *
     * @return integer
     */
    public function getAprobacion()
    {
        return $this->aprobacion;
    }

    /**
     * Add notificacione
     *
     * @param Notificacion $notificacione
     *
     * @return Encuesta
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

    /**
     * Set persona
     *
     * @param Persona $persona
     *
     * @return Encuesta
     */
    public function setPersona(Persona $persona = null)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get persona
     *
     * @return string
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * @return string
     */
    public function getGenero()
    {
        return $this->genero;
    }

    /**
     * @param string $genero
     */
    public function setGenero($genero)
    {
        $this->genero = $genero;
    }

    /**
     * Set policlinico
     *
     * @param Policlinico $policlinico
     *
     * @return Encuesta
     */
    public function setPoliclinico(Policlinico $policlinico = null)
    {
        $this->policlinico = $policlinico;

        return $this;
    }

    /**
     * Get policlinico
     *
     * @return string
     */
    public function getPoliclinico()
    {
        return $this->policlinico;
    }
}
