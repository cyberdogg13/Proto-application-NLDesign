<?php

// src/Controller/ProcessController.php

namespace App\Controller;

use Conduction\CommonGroundBundle\Service\ApplicationService;
//use App\Service\RequestService;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;



/**
 * The Procces controller handles any calls that have not been picked up by another controller, and wel try to handle the slug based against the wrc
 *
 * Class ProcessController
 * @package App\Controller
 * @Route("/process")
 */
class ProcessController extends AbstractController
{
    /**
     * This function shows all available processes
     *
     * @Route("/")
     * @Template
     */
    public function indexAction(Session $session, Request $request, CommonGroundService $commonGroundService, ApplicationService $applicationService, ParameterBagInterface $params){
        $variables = $applicationService->getVariables();
        $variables['processes'] = $commonGroundService->getResourceList(['component'=>'ptc','type'=>'process_types'])['hydra:member'];
        return $variables;
    }

    /**
     * This function will kick of the suplied proces with given values
     *
     * @Route("/{id}/start")
     */
    public function startAction(Session $session, $id, Request $request, CommonGroundService $commonGroundService, ApplicationService $applicationService, ParameterBagInterface $params){
        $session->set('request',null);

        return $this->redirect($this->generateUrl('app_process_load',['id'=>$id]));
    }

	/**
     * This function will kick of the suplied proces with given values
     *
	 * @Route("/{id}")
	 * @Route("/{id}/{slug}", name="app_process_slug")
	 * @Template
	 */
    public function loadAction(Session $session, $id, string $slug = 'instruction',Request $request, CommonGroundService $commonGroundService, ApplicationService $applicationService, ParameterBagInterface $params)
    {
        $variables = $applicationService->getVariables();

        $variables['request'] = $session->get('request', false);
        if(!$variables['request']){$variables['request'] = ['properties'=>[]];}


        if($request->isMethod('POST')){


            // the second argument is the value returned when the attribute doesn't exist
            $resource = $request->request->all();

            // Merge with teh request in session
            if($session->get('request')){
                $request =  $resource['request'];
                $request['properties'] = array_merge($session->get('request', [])['properties'], $resource['request']['properties']);
            }
            else{
                $request =  $resource['request'];
            }

            $variables['request'] = $commonGroundService->saveResource($request, ['component'=>'vrc','type'=>'requests']);

            // stores an attribute in the session for later reuse
            $session->set('request', $variables['request']);

            // Lets go to hte next stage
            if(key_exists('currentStage', $variables['request']) && $variables['request']['currentStage']){
                $slug = $variables['request']['currentStage'];
            }


        }

        $variables['process'] = $commonGroundService->getResource(['component'=>'ptc','type'=>'process_types','id'=>$id]);

        if(
            $slug == 'instruction' &&
            key_exists('request',$variables) &&
            key_exists('currentStage', $variables['request']) &&
            $variables['request']['currentStage']){
            $variables['stage'] = $commonGroundService->getResource($variables['request']['currentStage']);
        }
        elseif($slug != 'home'){
            foreach($variables['process']['stages'] as $stage){
                if($stage['slug'] == $slug){
                    $variables['stage'] = $stage;
                }

            }

            if(!key_exists('stage',$variables)){
                $variables['stage']['slug'] = $slug;
            }
        }
        else{
            foreach($variables['process']['stages'] as $stage){
                if($stage['start'])
                    $variables['stage'] = $stage;
            }
        }

        $variables["slug"] = $slug;

        return $variables;
    }

}






