<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use AppBundle\Entity\Article;

class CRUDController extends Controller
{
    private function createArticleForm($article) {
        // Form creation
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, array(
                 'attr' => array('placeholder' => 'titlePlaceholder' )
            ))
            ->add('content', CKEditorType::class)
            ->add('type', ChoiceType::class,array(
                'choices'  => array('Guide' => 'Guide','News' => 'News','Test' => 'Test','Jeux' => 'Games'),
            ))
            ->add('save', SubmitType::class, array( 'attr' => array('class' => 'waves-effect waves-light btn' ) ,'label' => 'CreateP'))
            ->getForm();

        return $form;
    }

    /**
     * @Route("/post/new", name="NewAction")
     */
    public function newAction(Request $request)
    {
        // see IvoryCKEditorBundle for WYSIWYG editor
        $article = new Article();
        //$article->setTitle('Blog title');
        $article->setContent('Entrez votre article');

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
            return $this->redirectToRoute('PostAction', array(
                'slug' => $article->getSlug(),
            ));
        }

        return $this->render('crud/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/post/{slug}", name="PostAction")
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
     * @Route("/post/{slug}/edit", name="EditAction")
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
                return $this->redirectToRoute('PostAction', array(
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
     * @Route("post/{slug}/delete", name="DeleteAction")
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

