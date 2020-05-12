<?php

namespace App\Controller;

use App\Entity\Personal;
use App\Form\PersonalType;
use Doctrine\DBAL\DriverManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PersonalController extends AbstractController
{
    /**
     * @Route("/personal", name="personal")
     */
    public function index()
    {
        $connectionParams = array(
            'url' => 'mysql://root:mercury@127.0.0.1:3306/PlanetShop',
        );
        $conn = DriverManager::getConnection($connectionParams);


        $sql = "SELECT * FROM personal;";
        $personals = $conn->query($sql);

        $count = $personals->rowCount();

        return $this->render('personal/index.html.twig', [
            'personals' => $personals,
            'count' => $count
        ]);

    }
    /**
     * @Route("/personal/create", name="create_personal")
     */
    public function create(\Symfony\Component\HttpFoundation\Request $request)
    {
        $personal = new Personal();
        $form = $this->createForm(PersonalType::class, $personal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $personal = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($personal);
            $em->flush();

            return $this->redirectToRoute('personal');
        }

        return $this->render('personal/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/personal/{id}", name="delete_personal")
     */
    public function delete(Personal $personal)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($personal);
        $em->flush();
        return $this->redirectToRoute('personal');
    }

    /**
     * @Route("personal/update/{personal}", name="update_personal")
     */
    public function update(\Symfony\Component\HttpFoundation\Request $request, Personal $personal)
    {


        $form = $this->createForm(PersonalType::class, $personal, [
            'action' => $this->generateUrl('update_personal', [
                'personal' => $personal->getId()]),
            'method' => 'POST']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personal = $form->getData();;

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('personal');
        }

        return $this->render('personal/form.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
