<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * NotificacionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NotificacionRepository extends EntityRepository
{
    public function verificarNotificacion($user)
    {
        $em = $this->getEntityManager();
        $nivelAcceso = $user->getNivelAcceso()->getId();
        $rol = 4;
        $dql = 'SELECT COUNT(n.id) AS total
                FROM AppBundle:Notificacion n
                JOIN n.rol r
                JOIN n.nivel a
                WHERE n.estado =:p1
                AND r.id =:p2
                AND a.id =:p3';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', true);
        $query->setParameter('p2', $rol);
        $query->setParameter('p3', $nivelAcceso);

        return $query->getResult();

    }
}
