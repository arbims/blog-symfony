<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController {

  private $em;

  public function __construct(ManagerRegistry $em)
  {
    $this->em = $em->getManager();  
  }
  /**
   * @Route("/admin/categories", name="admin_categories")
   */
  public function index(CategoryRepository $categoriesRepository) {
    $categories = $categoriesRepository->findAll();
    return $this->render("/admin/categories/index.html.twig", compact('categories'));
  }

  /**
   * @Route("/admin/categories/add", name="admin_categories_add")
   */
  public function add(Request $request) {

    $category = new Category();
    $form = $this->createForm(CategoryType::class, $category);
    $form->handleRequest($request);
      
    if ($form->isSubmitted() && $form->isValid()) {
      
      $this->em->persist($category);
      $this->em->flush();
      $this->addFlash('success', 'Category bien ajouter');
      return $this->redirectToRoute('admin_categories');
    }
    return $this->render("/admin/categories/add.html.twig",[
      'form' => $form->createView(),
      'category' => $category
    ]);
  }

}