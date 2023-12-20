<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Municipio;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * MunicipioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MunicipioRepository extends EntityRepository
{
    public function  agregarMunicipio($data)
    {
        try{
            $em = $this->getEntityManager();
            $municipio = new Municipio();
            $this->saveMunicipio($municipio,$data);

            $provincia  = $em->getRepository('AppBundle:Provincia')->find($data['idProvincia']);
            $this->saveProvincia($provincia,$municipio);

            $em->persist($municipio);
            $em->flush();
            $msg = $municipio;

        }catch (Exception $e)
        {
            if(strpos($e->getMessage() , 'Duplicate entry') > 0)
            {
                $msg = 'El municipio ya existe, no se puede agregar';
            }
            else
            {
                $msg = 'Se produjo un error al insertar el municipio';
            }
        }

        return $msg;
    }

    public function modificarMunicipio($data)
    {

        try {
            $em = $this->getEntityManager();
            $municipio = $em->getRepository('AppBundle:Municipio')->find($data['id']);

            if (!empty($municipio)) {
                $this->saveMunicipio($municipio,$data);

                $provincia  = $em->getRepository('AppBundle:Provincia')->find($data['idProvincia']);
                $this->saveProvincia($provincia,$municipio);

                $em->flush();
                $msg = $municipio;
            } else {
                $msg = $municipio;
            }

        } catch (Exception $e) {
            $msg = 'Se produjo un error al modificar el municipio';
        }

        return $msg;
    }

    public function eliminarMunicipio($id)
    {
        try {
            $em = $this->getEntityManager();
            $municipio = $em->getRepository('AppBundle:Municipio')->find($id);

            if (!empty($municipio)) {
                $em->remove($municipio);
                $em->flush();
                $msg = $municipio;
            } else {
                $msg = $municipio;
            }

        } catch (Exception $e) {

            if (strpos($e->getMessage(), 'foreign key') > 0) {
                $msg = 'Existen datos asociados a este municipio, no se puede eliminar';
            } else {
                $msg = 'Se produjo un error al eliminar el municipio';
            }
        }
        return $msg;
    }

    private function saveMunicipio($municipio,$data){
        $municipio->setNombre($data['nombre']);
        $municipio->setCodigo($data['codigo']);
    }

    private function saveProvincia($provincia,$municipio){
        $municipio->setProvincia($provincia);
    }
}
