<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Traza;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * TrazaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TrazaRepository extends EntityRepository
{
    public function guardarTraza($user,$dataTraza)
    {
        try{
            $fecha = new DateTime('now');

            $em = $this->getEntityManager();
            $traza = new Traza();
            $traza->setFecha($fecha);
            $traza->setModulo($dataTraza['modulo']);
            $traza->setAccion($dataTraza['accion']);
            $traza->setDescripcion($dataTraza['descripcion']);

            $usuario = $em->getRepository('AppBundle:Usuario')->find($user->getId());
            $traza->setUsuario($usuario);

            $em->persist($traza);
            $em->flush();
            $msg = $traza;

        }catch (Exception $e)
        {
            $msg = 'Se produjo un error al insertar la traza';
        }
        return $msg;
    }

    public function obtenerAcciones()
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT t.accion, COUNT(t.id) as cantidad
                FROM AppBundle:Traza t
                GROUP BY t.accion
                ORDER BY cantidad DESC';

        return $em->createQuery($dql)->getResult();
    }

    public function obtenerAccionesUsuarios($accion)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT u.nombre as usuario, COUNT(t.id) as cantidad
                FROM AppBundle:Traza t
                JOIN t.usuario u
                WHERE t.accion = :p1
                GROUP BY u.nombre
                ORDER BY cantidad DESC';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $accion);

        return $query->getResult();
    }
}
