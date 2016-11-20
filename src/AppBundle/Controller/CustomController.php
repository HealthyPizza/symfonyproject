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
     * @Route("/lucky/{value}", name="blog_show",requirements={"value": "\d+"})
     */
    public function showAction(Request $request,$value=100)
    {
        $number = mt_rand(0, $value);
		$session = $request->getSession();
		$login='';
		$loged=false;
		if($session->has('login')){
			$login=$session->get('login');
			$loged=true;
		}
		$repository = $this->getDoctrine()->getRepository('AppBundle:Article');
		$products = $repository->findAll();
		
		
		 return $this->render('lucky/number.html.twig', array(
            'number' => $number, 'login' => $login,'loged' => $loged,'articles' => $products,
        ));
    }
	
	/**
     * @Route("/lucky/{slug}", name="blog_slug")
     */
    public function slugAction(Request $request,$slug)
    {
		$session = $request->getSession();
		$session->remove('login');
		//$session->set('login', "ElBarto347");
		$product = new Article();
		$product->setTitle('Des lycéens louent une chèvre pour une fête et s’amusent à la brutaliser');
		$product->setContent("En septembre dernier, aux États-Unis, des adolescents ont loué une chèvre – pour 10 dollars (9 euros)  auprès de l’un de leurs amis – afin de  « s’amuser » à l’occasion d’une fête d’anniversaire et l’ont gravement maltraitée, la laissant dans un état critique.\n
		C’est un animal profondément traumatisé qui est accueilli, quelques jours plus tard, au sanctuaire Goats of Anarchy (GOA), dans le New-Jersey. La petite chèvre, âgée d’à peine 6 mois, regarde le sol en permanence et est incapable de regarder quelqu’un des les yeux. Elle est visiblement terrifiée à l’idée d’être touchée.\nEt pour cause, Grace a vécu l’enfer : comme le témoigne une vidéo publiée sur Facebook par l’un des jeunes présents à la fête d’anniversaire organisée par les lycéens de l’Arkansas, plusieurs adolescents ont d’abord ouvert leurs canettes de bière sur le crâne du frêle animal tandis que l’un d’entre eux la tenait par le cou.
Divers témoignages de personnes présentes au moment des faits affirment qu’au moins cinq des convives « se sont vantés de lui avoir fait avaler, de force, de la bière et des mégots de cigarettes ». Des SMS échangés par les lycéens viendront confirmer cette version des faits.
		
		");
		$product->setDate(new \DateTime());
    	$em = $this->getDoctrine()->getManager();
		$em->persist($product);
    	$em->flush();
        return $this->redirectToRoute('blog_show');
        
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