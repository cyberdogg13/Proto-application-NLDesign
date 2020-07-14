<?php

namespace App\Controller;

use Conduction\CommonGroundBundle\Service\ApplicationService;

use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


/**
 * The Education controller ...
 *
 * Class EducationController
 * @package App\Controller
 * @Route("/edu")
 */
class EducationController extends AbstractController


{/**
     * @Route("/oplossingen/{id}")
     * @Template
     */
    public function oplossingAction(Session $session, Request $request, ApplicationService $applicationService, CommonGroundService $commonGroundService, ParameterBagInterface $params, $id)
    {
        $content = false;
        $variables = $applicationService->getVariables();

        // Lets provide this data to the template
        $variables['query'] = $request->query->all();
        $variables['post'] = $request->request->all();

        // Lets find an appoptiate slug
        $template = $commonGroundService->getResource(['component' => 'wrc', 'type' => 'applications', 'id' => $params->get('app_id') . '/oplossing']);// Lets see if there is a post to procces

        // Get resource
        $variables['resource'] = $commonGroundService->getResource(['component' => 'chrc', 'type' => 'pitches', 'id' => $id]);

        if ($request->isMethod('POST')) {

            // Make a review/comment
            if (isset($_POST['add_comment'])) {

                $resource['author'] = $variables['user']['@id'];
                $resource['resource'] = $variables['resource']['@id'];
                $resource['review'] = $request->request->get('review');

                $resource = $commonGroundService->createResource($resource, ['component' => 'rc', 'type' => 'reviews']);
            } else {

                $resource = $request->request->all();

                if (key_exists('@component', $resource)) {
                    // Passing the variables to the resource
                    $configuration = $commonGroundService->saveResource($resource, ['component' => $resource['@component'], 'type' => $resource['@type']]);
                }
            }
        }

        // Get all reviews/comments of this resource
        $variables['comments'] = $commonGroundService->getResourceList(['component' => 'rc', 'type' => 'reviews'],['resource' => $variables['resource']['@id']]);

        if ($template && array_key_exists('content', $template)) {
            $content = $template['content'];
        }

        // Create the template
        if ($content) {
            $template = $this->get('twig')->createTemplate($content);
            $template = $template->render($variables);
        } else {
            $template = $this->render('404.html.twig', $variables);
            return $template;
        }

        return $response = new Response(
            $template,
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

    //W.I.P.
    /**
     * @Route("/studenten/{id}")
     * @Template
     */
    public function studentAction(Session $session, Request $request, ApplicationService $applicationService, CommonGroundService $commonGroundService, ParameterBagInterface $params, $id)
    {
        $content = false;
        $variables = $applicationService->getVariables();

        // Lets provide this data to the template
        $variables['query'] = $request->query->all();
        $variables['post'] = $request->request->all();

        // Lets find an appoptiate slug
        $template = $commonGroundService->getResource(['component' => 'wrc', 'type' => 'applications', 'id' => $params->get('app_id') . '/student']);
        $variables['resource'] = $commonGroundService->getResource(['component' => 'edu', 'type' => 'participants', 'id' => $id]);

        if ($template && array_key_exists('content', $template)) {
            $content = $template['content'];
        }

        // Lets see if there is a post to procces
        if ($request->isMethod('POST')) {
            $resource = $request->request->all();
            if (key_exists('@component', $resource)) {
                // Passing the variables to the resource
                $configuration = $commonGroundService->saveResource($resource, ['component' => $resource['@component'], 'type' => $resource['@type']]);
            }
        }


        // Create the template
        if ($content) {
            $template = $this->get('twig')->createTemplate($content);
            $template = $template->render($variables);
        } else {
            $template = $this->render('404.html.twig', $variables);
            return $template;
        }

        return $response = new Response(
            $template,
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

}






