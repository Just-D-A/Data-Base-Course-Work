<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Customer;
use App\Entity\SpaceObject;
use App\Form\SpaceObjectType;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SpaceObjectController extends AbstractController
{
    /**
     * @Route("/space_object/{id}", name="space_object")
     */
    public function index($id)
    {

        $connectionParams = array(
            'url' => 'mysql://root:mercury@127.0.0.1:3306/PlanetShop',
        );
        $conn = DriverManager::getConnection($connectionParams);

        $sql = " SELECT * FROM space_object WHERE id_customer_owner is NULL;";
        $space_objectes = $conn->query($sql);

        $count = $space_objectes->rowCount();

        $em = $this->getDoctrine()->getManager();
        $customer = $em->getRepository(Customer::class)->find($id);

        return $this->render('space_object/index.html.twig', [
            'customer' => $customer,
            'space_objectes' => $space_objectes,
            'id' => $id,
            'count' => $count
        ]);

    }

    /**
     * @Route("/space_object/create/{id}", name="create_space_object")
     * -             */
    public function create(\Symfony\Component\HttpFoundation\Request $request, $id)
    {
        $space_object = new SpaceObject();
        $form = $this->createForm(SpaceObjectType::class, $space_object);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $space_object = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($space_object);
            $em->flush();

            return $this->redirectToRoute('space_object', [
                'id' => $id
            ]);
        }

        return $this->render('space_object/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/space_object/{id}/{space_object}", name="delete_space_object")
     */
    public function delete($id, SpaceObject $space_object)
    {
        $connectionParams = array(
            'url' => 'mysql://root:mercury@127.0.0.1:3306/PlanetShop',
        );
        $conn = DriverManager::getConnection($connectionParams);
        $id_space_object = $space_object->getId();

        $sql = " DELETE FROM cart WHERE id_space_object = '$id_space_object';";
        $conn->query($sql);


        $em = $this->getDoctrine()->getManager();
        $em->remove($space_object);
        $em->flush();

        return $this->redirectToRoute('space_object', [
            'id' => $id
        ]);
    }

    /**
     * @Route("space_object/update/{id}/{space_object}", name="update_space_object")
     */
    public function update(\Symfony\Component\HttpFoundation\Request $request, $id, SpaceObject $space_object)
    {


        $form = $this->createForm(SpaceObjectType::class, $space_object);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $space_object = $form->getData();;

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('space_object', [
                'id' => $id
            ]);
        }

        return $this->render('space_object/form.html.twig', [
            'form' => $form->createView()
        ]);

    }

}
