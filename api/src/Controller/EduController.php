<?php

// src/Controller/ZuidDrechtController.php

namespace App\Controller;

use Conduction\CommonGroundBundle\Service\ApplicationService;
//use App\Service\RequestService;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use phpDocumentor\Reflection\Type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * The Tender controller handles any calls for tenders.
 *
 * Class TenderController
 *
 * @Route("/education")
 */
class EduController extends AbstractController
{
    /**
     * @Route("/")
     * @Template
     */
    public function indexAction(Session $session, Request $request, ApplicationService $applicationService, CommonGroundService $commonGroundService, ParameterBagInterface $params)
    {
        $content = false;
        $variables = $applicationService->getVariables();

        // Lets provide this data to the template
        $variables['query'] = $request->query->all();
        $variables['post'] = $request->request->all();

        // Get resource
        $variables['programs'] = $commonGroundService->getResource(['component' => 'edu', 'type' => 'programs'], $variables['query']);
        $variables['courses'] = $commonGroundService->getResource(['component' => 'edu', 'type' => 'courses'], $variables['query']);

        return $variables;
    }

    /**
     * @Route("/programs")
     * @Template
     */
    public function programsAction(Session $session, Request $request, ApplicationService $applicationService, CommonGroundService $commonGroundService, ParameterBagInterface $params)
    {
        $content = false;
        $variables = $applicationService->getVariables();

        // Lets provide this data to the template
        $variables['query'] = $request->query->all();
        $variables['post'] = $request->request->all();

        // Get resource
        $variables['resources'] = $commonGroundService->getResource(['component' => 'edu', 'type' => 'programs'], $variables['query']);

        return $variables;
    }

    /**
     * @Route("/programs/{id}")
     * @Template
     */
    public function programAction(Session $session, Request $request, ApplicationService $applicationService, CommonGroundService $commonGroundService, ParameterBagInterface $params, )
    {
        $content = false;
        $variables = $applicationService->getVariables();

        // Lets provide this data to the template
        $variables['id'] = $id;
        $variables['query'] = $request->query->all();
        $variables['post'] = $request->request->all();

        // Get resource
        $variables['resources'] = $commonGroundService->getResource(['component' => 'edu', 'type' => 'programs','id' => $id], $variables['query']);

        return $variables;
    }

    /**
     * @Route("/courses")
     * @Template
     */
    public function coursesAction(Session $session, Request $request, ApplicationService $applicationService, CommonGroundService $commonGroundService, ParameterBagInterface $params)
    {
        $content = false;
        $variables = $applicationService->getVariables();

        // Lets provide this data to the template
        $variables['query'] = $request->query->all();
        $variables['post'] = $request->request->all();

        // Get resource
        $variables['resources'] = $commonGroundService->getResource(['component' => 'edu', 'type' => 'courses'], $variables['query']);

        return $variables;
    }

    /**
     * @Route("/courses/{id}")
     * @Template
     */
    public function courseAction(Session $session, Request $request, ApplicationService $applicationService, CommonGroundService $commonGroundService, ParameterBagInterface $params, )
    {
        $content = false;
        $variables = $applicationService->getVariables();

        // Lets provide this data to the template
        $variables['id'] = $id;
        $variables['query'] = $request->query->all();
        $variables['post'] = $request->request->all();

        // Get resource
        $variables['resources'] = $commonGroundService->getResource(['component' => 'edu', 'type' => 'courses','id' => $id], $variables['query']);

        return $variables;
    }
}
