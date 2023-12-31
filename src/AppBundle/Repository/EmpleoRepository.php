<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Empleo;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * EmpleoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EmpleoRepository extends EntityRepository
{
    public function agregarEmpleo($data)
    {
        try {
            $em = $this->getEntityManager();
            $empleo = new Empleo();
            $this->saveEmpleo($empleo,$data);

            $em->persist($empleo);
            $em->flush();
            $msg = $empleo;

        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') > 0) {
                $msg = 'El empleo ya existe, no se puede agregar';
            } else {
                $msg = 'Se produjo un error al insertar el empleo';
            }
        }
        return $msg;
    }

    public function modificarEmpleo($data)
    {
        try {
            $em = $this->getEntityManager();
            $empleo = $em->getRepository('AppBundle:Empleo')->find($data['id']);

            if (!empty($empleo)) {
                $this->saveEmpleo($empleo,$data);

                $em->flush();
                $msg = $empleo;
            } else {
                $msg = $empleo;
            }

        } catch (Exception $e) {
            $msg = 'Se produjo un error al modificar el empleo';
        }

        return $msg;
    }

    public function eliminarEmpleo($id)
    {
        try {
            $em = $this->getEntityManager();
            $empleo = $em->getRepository('AppBundle:Empleo')->find($id);

            if (!empty($empleo)) {
                $em->remove($empleo);
                $em->flush();
                $msg = $empleo;
            } else {
                $msg = $empleo;
            }

        } catch (Exception $e) {

            $msg = 'Se produjo un error al eliminar el empleo';

        }
        return $msg;
    }

    private function saveEmpleo($empleo,$data){
        $empleo->setNombre($data['nombre']);
    }
}
