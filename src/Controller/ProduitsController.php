<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\AddProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ProduitsController extends AbstractController
{
    /**
     * @Route("/produits", name="app_produits")
     */
    public function index(Request $req): Response
    {

        $produit = new Produit();
        $pForm = $this->createForm(AddProduitType::class, $produit);
        $pForm->handleRequest($req);
        return $this->render('produits/index.html.twig', [
            "pForm" => $pForm->createView()
        ]);
    }

    /**
     * @Route("/produit/add", name="app_produits_add")
     */
    public function add(Request $req, EntityManagerInterface $em, ProduitRepository $produitRepository): Response
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $data = json_decode($req->getContent());
        $produit = new Produit();

        $produit->setName($data->name);
        $produit->setDescription($data->description);
        $produitRepository->add($produit);

        $produits = $produitRepository->findAll();

        $jsonContent = $serializer->serialize($produits, 'json');

        return $this->json($jsonContent);
    }
}
