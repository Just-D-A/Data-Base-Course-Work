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
}
