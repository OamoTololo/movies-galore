<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Utils\CategoryTreeAdminList;
use App\Utils\CategoryTreeAdminOptionList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/my_profile.html.twig');
    }

    /**
     * @Route("/categories", name="app_admin_categories", methods={"GET", "POST"})
     */
    public function categories(CategoryTreeAdminList $categories, Request $request): Response
    {
        $categories->getCategoryList($categories->buildTree());

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $is_invalid = null;

        if ($this->saveCategory($category, $form, $request)) {
            return $this->redirectToRoute('app_admin_categories');
        }elseif ($request->isMethod('post')) {
            $is_invalid = ' is-invalid';
        }

        return $this->render('admin/categories.html.twig',[
            'categories' => $categories->categorylist,
            'form' => $form->createView(),
            'is_invalid' => $is_invalid
        ]);
    }

    /**
     * @Route("/edit-category/{id}", name="app_admin_edit_category", methods={"GET", "POST"})
     */
    public function editCategory(Category $category, Request $request): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $is_invalid = null;

        if ($this->saveCategory($category, $form, $request)) {
            return $this->redirectToRoute('app_admin_categories');
        }elseif ($request->isMethod('post')) {
            $is_invalid = ' is-invalid';
        }

        return $this->render('admin/edit_category.html.twig',[
            'category' => $category,
            'form' => $form->createView(),
            'is_invalid' => $is_invalid
        ]);
    }

    /**
     * @Route("/delete-category/{id}", name="app_admin_delete_category")
     */
    public function deleteCategory(Category $category): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin_categories');
    }

    /**
     * @Route("/upload-video", name="app_admin_upload_video")
     */
    public function upload(): Response
    {
        return $this->render('admin/upload_video.html.twig');
    }

    /**
     * @Route("/users", name="app_admin_users")
     */
    public function users(): Response
    {
        return $this->render('admin/users.html.twig');
    }

    /**
     * @Route("/videos", name="app_admin_videos")
     */
    public function videos(): Response
    {
        return $this->render('admin/videos.html.twig');
    }

    public function getAllCategories(CategoryTreeAdminOptionList $categories, $editedCategory = null)
    {
        $categories->getCategoryList($categories->buildTree());
        return $this->render('admin/_all_categories.html.twig',[
            'categories' => $categories,
            'editedCategory' => $editedCategory
        ]);
    }

    private function saveCategory($category, $form, $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setName($request->request->get('category')['name']);

            $repository = $this->getDoctrine()->getRepository(Category::class);
            $parent = $repository->find($request->request->get('category')['parent']);
            $category->setParent($parent);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return true;
        }
        return false;
    }
}
