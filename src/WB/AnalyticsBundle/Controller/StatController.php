<?php

namespace WB\AnalyticsBundle\Controller;

use WB\AnalyticsBundle\Document\WBLine;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WB\AnalyticsBundle\Services\Stat\AnalyticsException;


class StatController extends Controller 
{
	
/*
 * Main action
 */
    public function indexAction()
    {
        return new Response("success");
    }


/*
 * Collect datas action
 */
	public function collectAction()
	{
		
	// Create new document
		$wbLine = new \WB\AnalyticsBundle\Document\WBLine();
		
	// Extract and anayse parameters
		try
		{
			$wbLine->extractParameters();
			$wbLine->applyRules();
			$wbLine->hydrate();
		}
		catch(AnalyticsException $e)
		{
			return $this->render
			(
				'WBAnalyticsBundle:Stat:error.json.twig', array
				(
					'data'	=> $e->getDetails()
				)
			);
		}
		
	// If user is defined, check if exists
		$wui = $wbLine->getWizBeeNaute();
		if ($wui != 0)
			if ($this->getDoctrine()->getManager()->getRepository('WBWizbiinauteBundle:Wizbiinaute')->findOneBy(array('wui' => $wui)) === null)
				throw new NotFoundHttpException("L'utilisateur d'id ".$wui." n'existe pas.");		

	// Get Mongo manager
		$dm = $this->get('doctrine_mongodb')->getManager();
	
	// Check if line has still been registred
		$line = $dm
			->getRepository('WBAnalyticsBundle:WBLine')
			->findOneBy(array('uniquetemporalhash' => $uniqueTemporalHash));
		if ($line !== null)
		{
			return new Response("Work still done.");
		}

	// Add to MongoDB
		$dm->persist($wbLine);
		$dm->flush();

	// Go to main route
		return $this->redirectToRoute('wb_analytics_home');
		
    }
}