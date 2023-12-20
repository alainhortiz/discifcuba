<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Exception;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use AppBundle\Entity\Usuario;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * UsuarioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UsuarioRepository extends EntityRepository
{

    public function listarEntrevistadores()
    {
        $em = $this->getEntityManager();
        $dql = 'SELECT u FROM AppBundle:Usuario u 
                JOIN u.usuario_roles ur
                WHERE u.isActive = :p1
                AND ur.id = :p2';
        $query = $em->createQuery($dql);
        $query->setParameter('p1', true);
        $query->setParameter('p2', 4);
        return $query->getResult();
    }

    public function cambiarPassword($idUsuario, $passNew)
    {
        try {
            $em = $this->getEntityManager();
            $user = $em->getRepository('AppBundle:Usuario')->find($idUsuario);

            $encoder = new BCryptPasswordEncoder(12);
            $passNew = $encoder->encodePassword($passNew, null);

            if (!empty($user)) {
                $user->setPassword($passNew);
                $user->setIsActive(true);

                $em->persist($user);
                $em->flush();
                $msg = $user;
            } else {
                $msg = $user;
            }

        } catch (Exception $e) {
            $msg = 'Se produjo un error al cambiar la contraseña';
        }
        return $msg;

    }

    public function resetearPassword($idUsuario)
    {
        try {
            $em = $this->getEntityManager();
            $user = $em->getRepository('AppBundle:Usuario')->find($idUsuario);

            if (!empty($user)) {
                $encoder = new BCryptPasswordEncoder(12);
                $passNew = $encoder->encodePassword($user->getUsername(), null);
                $user->setPassword($passNew);
                $em->flush();
                $msg = $user;
            } else {
                $msg = $user;
            }

        } catch (Exception $e) {

            $msg = 'Se produjo un error al cambiar la contraseña';

        }
        return $msg;

    }

    public function agregarUsuario($data)
    {
        try {

            $em = $this->getEntityManager();
            $user = new Usuario();
            $this->saveUsuario($user, $data);

            $profesion = $em->getRepository('AppBundle:Profesion')->find($data['profesion']);
            $user->setProfesion($profesion);

            $nivelAcceso = $em->getRepository('AppBundle:NivelAcceso')->find($data['nivelAcceso']);
            $user->setNivelAcceso($nivelAcceso);

            $encoder = new BCryptPasswordEncoder(12);
            $encoded = $encoder->encodePassword($data['username'], null);
            $user->setPassword($encoded);

            $usuarioRoles = new ArrayCollection();

            foreach ($data['roles'] as $role) {
                $usuarioRoles[] = $em->getRepository('AppBundle:Role')->find($role);
            }
            $user->setUsuarioRoles($usuarioRoles);

            $em->persist($user);
            $em->flush();
            $msg = $user;

        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') > 0) {
                $msg = 'El Usuario ya existe, no se puede agregar';
            } else {
                $msg = $e->getMessage();
            }
        }
        return $msg;
    }

    public function modificarUsuario($data)
    {
        try {
            $em = $this->getEntityManager();
            $user = $em->getRepository('AppBundle:Usuario')->find($data['idUsuario']);

            if (!empty($user)) {
                $this->saveUsuario($user, $data);

                $profesion = $em->getRepository('AppBundle:Profesion')->find($data['profesion']);
                $user->setProfesion($profesion);

                $nivelAcceso = $em->getRepository('AppBundle:NivelAcceso')->find($data['nivelAcceso']);
                $user->setNivelAcceso($nivelAcceso);

                $usuarioRoles = new ArrayCollection();

                foreach ($data['roles'] as $role) {
                    $usuarioRoles[] = $em->getRepository('AppBundle:Role')->findOneBy(array('nombre' => $role));
                }
                $user->setUsuarioRoles($usuarioRoles);

                $em->flush();
                $msg = $user;
            } else {
                $msg = $user;
            }

        } catch (Exception $e) {
            $msg = 'Se produjo un error al modificar el Usuario';
        }

        return $msg;
    }

    public function eliminarUsuario($id)
    {
        try {
            $em = $this->getEntityManager();
            $user = $em->getRepository('AppBundle:Usuario')->find($id);

            if (!empty($user)) {
                $user->setIsActive(0);

                $em->persist($user);
                $em->flush();
                $msg = $user;
            } else {
                $msg = $user;
            }

        } catch (Exception $e) {
            $msg = 'Se produjo un error al eliminar el Usuario';
        }
        return $msg;
    }

    private function saveUsuario($user, $data)
    {
        $user->setUsername($data['username']);
        $user->setNombre($data['nombre']);
        $user->setPrimerApellido($data['primerApellido']);
        $user->setSegundoApellido($data['segundoApellido']);
        $user->setSupervisor($data['supervisor']);
    }

}