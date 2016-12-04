<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Article;


class CustomController extends Controller
{

    /**
     * @Route("/", name="blog_show")
     */
    public function showAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Article');
        $articles = $repository->getPage();
        return $this->render('posts/post_list.html.twig', array(
            'articles' => $articles, 'maxpage' => $repository->getPageCount(), 'page' => 1,
        ));
    }

    /**
     * @Route("/pages/{numPage}", name="blog_page", requirements={"numPage": "\d+"})
     */
    public function slugAction(Request $request,$numPage) //a revoir -surcharge du find -
    {
        $nextpage=false;
        if($numPage<=1)
            return $this->redirectToRoute('blog_show');
        $repository = $this->getDoctrine()->getRepository('AppBundle:Article');
        $articles = $repository->getPage($numPage);
        if($articles==null)
            return $this->redirectToRoute('blog_show');
        
        return $this->render('posts/post_list.html.twig', array(
            'articles' => $articles, 'maxpage' => $repository->getPageCount(), 'page' => $numPage,
        ));
    }

    /**
     * @Route("/{url}", name="remove_trailing_slash",
     *     requirements={"url" = ".*\/$"}, methods={"GET"})
     */
    public function removeTrailingSlashAction(Request $request)
    {
        $session = $request->getSession();
        if($session->has('errCount')){
            $errors=$session->get('errCount');
            $errors++;
            $session->set('errCount', $errors);
        }
        else
            $session->set('errCount', 1);

        return $this->redirectToRoute('blog_show');
    }
}
