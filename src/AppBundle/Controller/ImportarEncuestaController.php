<?php

namespace AppBundle\Controller;

use AppBundle\ImportacionExcel\ImportadorExcel;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\File;

class ImportarEncuestaController extends Controller
{
    /**
     * @Route("/formImportarEncuesta", name="formImportarEncuesta")
     */
    public function formImportarEncuestaAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('encuesta', FileType::class,[
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ],
                        'mimeTypesMessage' => 'Por favor seleccione un documento excel.',
                    ])
                ],
            ])
            ->add('importar', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            // Los datos están en un array con los keys "name", "email", y "message"
            $data = $form->get('encuesta')->getData();
            if ($data) {
                $originalFilename = pathinfo($data->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid('', true).'.'.$data->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $data->move("plantillas/", $newFilename);
                    $fichero_subido = 'plantillas/' . $newFilename;
                    $excel = new ImportadorExcel();
                    $resp = $excel->importarDatos($fichero_subido);
//                    $em = $this->getDoctrine()->getManager();
//                    $user = $this->getUser();
//                    foreach ($resp as $r) {
//                        if($r['valido']){
//                            $resp = $em->getRepository('AppBundle:Encuesta')->masterImportarEncuesta($user, $r);
//
////                            if (is_string($resp)) {
////                                //Escribir que valido es false
////                            }
//
//                        }
//                    }
//                    var_dump($resp);
//                    exit();
                    #borrar fichero temporal subido
                    unlink($fichero_subido);
                    return $this->render('Encuestas/importarEncuestaResultado.html.twig', array(
                        'resultados' => $resp
                    ));
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                //Excel
            }

        }

        // ... renderizar el formulario
        return $this->render('Encuestas/importarEncuesta2.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/importarEncuesta", name="importarEncuesta")
     */
    public function importarEncuestaAction()
    {

        return $this->render('Encuestas/importarEncuesta.html.twig');
    }

    /**
     * @Route("/obtenerDocumento", name="obtenerDocumento")
     */
    public function obtenerDocumentoAction()
    {
        #El nombre original del fichero en la máquina del cliente.
        $adjuntoNombre = $_FILES['fichero_usuario']['name'];
        #El nombre temporal del fichero en el cual se almacena el fichero subido en el servidor.
        $adjuntoNombreTemporal = $_FILES['fichero_usuario']['tmp_name'];

        $fichero_subido = 'plantillas/' . $adjuntoNombre;

        $resp = new ArrayCollection();
        if (move_uploaded_file($adjuntoNombreTemporal, $fichero_subido)) {
            $excel = new ImportadorExcel();
            $resp = $excel->importarDatos($fichero_subido);
            print_r ($resp);
            #borrar fichero temporal subido
            unlink($fichero_subido);
            $result = json_encode($resp);
            return new JsonResponse($result);
        }

        #borrar fichero temporal subido
        unlink($fichero_subido);
        $resp->add(array(
            'valido' => false,
            'motivo' => 'Problemas al obtener el documento excel'
        ));

        $result = json_encode($resp);
        return new JsonResponse($result);

    }
}
