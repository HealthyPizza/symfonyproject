<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use AppBundle\Entity\Article;

class CRUDController extends Controller
{
    private function createArticleForm($article) {
        // Form creation
        $form = $this->createFormBuilder($article)
                ->add('title', TextType::class)
                ->add('content', CKEditorType::class)
                ->add('save', SubmitType::class, array('label' => 'Create Post'))
                ->getForm();
        
        return $form;
    }
    
    /**
     * @Route("/post/new", name="post_new")
     */
    public function newAction(Request $request)
    {
        // see IvoryCKEditorBundle for WYSIWYG editor
        $article = new Article();
        $article->setTitle('Blog title');
        $article->setContent('Content');
        
        // Form creation
        $form = $this->createArticleForm($article);
        $form->handleRequest($request);
        
        // Form submittion handling
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            
            // Redirect to post page
            return $this->redirectToRoute('post_read', array(
                'slug' => $article->getSlug(),
            ));
        }
        
        return $this->render('crud/create.html.twig', array(
            'form' => $form->createView(),
        ));
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Article');
        $article = $repository->findOneBySlug($slug);
        if($article != null) {
            $form = $this->createArticleForm($article);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $article = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                // Redirect to post page
                return $this->redirectToRoute('post_read', array(
                    'slug' => $article->getSlug(),
                ));
           }
           
           return $this->render('crud/create.html.twig', array(
             'form' => $form->createView(),
           ));
        }
        else {
            return $this->render('crud/no_post.html.twig', array());
        }
    }
    
    /**
     * @Route("post/{slug}/delete", name="post_delete")
     */
    public function deleteAction(Request $request, $slug)
    {
        $manager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AppBundle:Article');
        $article = $repository->findOneBySlug($slug);
        if($article != null)
        {
            $manager->remove($article);
            $manager->flush();
        }        
        return $this->redirectToRoute('blog_show');
    }
}

