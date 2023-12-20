<?php

namespace AppBundle\Repository;

use AppBundle\Entity\SistemaAfectado;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * SistemaAfectadoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SistemaAfectadoRepository extends EntityRepository
{
    public function agregarSistemaAfectado($data)
    {
        try {
            $em = $this->getEntityManager();
            $sistemaAfectado = new SistemaAfectado();
            $this->saveSistema($sistemaAfectado,$data);

            $em->persist($sistemaAfectado);
            $em->flush();
            $msg = $sistemaAfectado;

        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') > 0) {
                $msg = 'El sistema afectado ya existe, no se puede agregar';
            } else {
                $msg = 'Se produjo un error al insertar el sistema afectado';
            }
        }
        return $msg;
    }

    public function modificarSistemaAfectado($data)
    {
        try {
            $em = $this->getEntityManager();
            $sistemaAfectado = $em->getRepository('AppBundle:SistemaAfectado')->find($data['id']);

            if (!empty($sistemaAfectado)) {
                $this->saveSistema($sistemaAfectado,$data);

                $em->flush();
                $msg = $sistemaAfectado;
            } else {
                $msg = $sistemaAfectado;
            }

        } catch (Exception $e) {
            $msg = 'Se produjo un error al modificar el sistema afectado';
        }

        return $msg;
    }

    public function eliminarSistemaAfectado($id)
    {
        try {
            $em = $this->getEntityManager();
            $sistemaAfectado = $em->getRepository('AppBundle:SistemaAfectado')->find($id);

            if (!empty($sistemaAfectado)) {
                $em->remove($sistemaAfectado);
                $em->flush();
                $msg = $sistemaAfectado;
            } else {
                $msg = $sistemaAfectado;
            }

        } catch (Exception $e) {

            $msg = 'Se produjo un error al eliminar el sistema afectado';

        }
        return $msg;
    }

    private function saveSistema($sistemaAfectado,$data){
        $sistemaAfectado->setNombre($data['nombre']);
    }
}
