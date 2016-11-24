<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Article;

class CRUDController extends Controller
{
    /**
     * @Route("/post/new", name="post_new")
     */
    public function newAction(Request $request)
    {
        // TODO
        return $this->redirectToRoute('blog_show');
    }
    
    /**
     * @Route("/post/{slug}", name="post_read")
     */
    public function postAction(Request $request, $slug)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Article');
        $article = $repository->findOneBySlug($slug);
        if($article != null)
        {
            return $this->render('crud/read.html.twig', array(
                'article' => $article
            ));
        }
        else
        {
            return $this->render('crud/no_post.html.twig', array());
        }
    }
    
    /**
     * @Route("/post/{slug}/edit", name="post_edit")
     */
    public function editAction(Request $request, $slug)
    {
        // TODO
        return $this->redirectToRoute('blog_show');
    }
    
    /**
     * @Route("post/{slug/delete", name="post_delete")
     */
    public function deleteAction(Request $request, $slug)
    {
        // TODO
        return $this->redirectToRoute('blog_show');
    }
}

