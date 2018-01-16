<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ArticleController
 *
 * @package AppBundle\Controller
 */
class ArticleController extends Controller
{
    /**
     * @Route("/article", name="article_homepage")
     * @Method("GET")
     */
    public function indexAction()
    {
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $article = $articleRepository->findAll();
        dump($article);
        return $this->render('article/index.html.twig', array(
            'article_list' => $article,
        ));
    }

    /**
     * @Route("/article/new", name="article_new")
     */
    public function createAction(Request $request)
    {
        $article = new Article();
        dump($article);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('article_homepage');
        }
        return $this->render('article/create.html.twig', [
            'create_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article/update/{id}", name="article_update")
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $article = $em->getRepository(Article::class)->find($id);
        dump($article);
        if ($article) {
            $updateForm = $this->createForm(ArticleType::class, $article);
            $updateForm->handleRequest($request);
            if ($updateForm->isValid()) {
                $em->persist($article);
                $em->flush();
                return $this->redirectToRoute('article_homepage');
            }
            return $this->render('article/update.html.twig', array('article' => $article,
                'edit_form' => $updateForm->createView()));
        }
        return $this->redirectToRoute('article_homepage');
    }

    /**
     * @Route("/article/delete/{id}", name="article_delete")
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Article::class)->find($id);
        dump($article);
        if (!$article) {
            throw $this->createNotFoundException('The article does not exist');
        }
        if ($article) {
            $em->remove($article);
            $em->flush();
        }
        return $this->redirectToRoute('article_homepage');
    }

    /**
     * @Route("/article/show/{id}", name="article_show")
     * @Method("GET")
     */
    public function showAction(Article $article)
    {
        dump($article);
        return $this->render('article/show.html.twig', array(
            'detail_form' => $article,
        ));
    }
}