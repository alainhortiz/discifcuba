<?php

namespace AppBundle\Repository;

use AppBundle\Entity\FuncionAfectado;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * FuncionAfectadoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FuncionAfectadoRepository extends EntityRepository
{
    public function agregarFuncionAfectado($data)
    {
        try {
            $em = $this->getEntityManager();
            $funcionAfectado = new FuncionAfectado();
            $this->saveFuncionAfectado($funcionAfectado,$data);

            $em->persist($funcionAfectado);
            $em->flush();
            $msg = $funcionAfectado;

        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') > 0) {
                $msg = 'La función afectada ya existe, no se puede agregar';
            } else {
                $msg = 'Se produjo un error al insertar la función afectada';
            }
        }
        return $msg;
    }

    public function modificarFuncionAfectado($data)
    {
        try {
            $em = $this->getEntityManager();
            $funcionAfectado = $em->getRepository('AppBundle:FuncionAfectado')->find($data['id']);

            if (!empty($funcionAfectado)) {
                $this->saveFuncionAfectado($funcionAfectado,$data);

                $em->flush();
                $msg = $funcionAfectado;
            } else {
                $msg = $funcionAfectado;
            }

        } catch (Exception $e) {
            $msg = 'Se produjo un error al modificar la función afectada';
        }

        return $msg;
    }

    public function eliminarFuncionAfectado($id)
    {
        try {
            $em = $this->getEntityManager();
            $funcionAfectado = $em->getRepository('AppBundle:FuncionAfectado')->find($id);

            if (!empty($funcionAfectado)) {
                $em->remove($funcionAfectado);
                $em->flush();
                $msg = $funcionAfectado;
            } else {
                $msg = $funcionAfectado;
            }

        } catch (Exception $e) {

            $msg = 'Se produjo un error al eliminar la función afectada';

        }
        return $msg;
    }

    public function obtenerNombreFunciones()
    {
        $em = $this->getEntityManager();

        $dql = "SELECT f.nombre as funcion
                FROM AppBundle:FuncionAfectado f
                ORDER BY f.id";

        return $em->createQuery($dql)->getResult();

    }

    private function saveFuncionAfectado($funcionAfectado,$data){
        $funcionAfectado->setNombre($data['nombre']);
    }

}