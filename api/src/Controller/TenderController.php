<?php

// src/Controller/ZuidDrechtController.php

namespace App\Controller;

use Conduction\CommonGroundBundle\Service\ApplicationService;

//use App\Service\RequestService;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


/**
 * The Tender controller handles any calls for tenders
 *
 * Class TenderController
 * @package App\Controller
 * @Route("/chrc")
 */
class TenderController extends AbstractController


{/**
 * @Route("/new-pitch")
 * @Template
 */
    public function newpitchAction(Session $session, Request $request, ApplicationService $applicationService, CommonGroundService $commonGroundService, ParameterBagInterface $params)
    {
        $content = false;
        $variables = $applicationService->getVariables();

        // Lets provide this data to the template
        $variables['query'] = $request->query->all();
        $variables['post'] = $request->request->all();

        // Lets find an appoptiate slug
        $template = $commonGroundService->getResource(['component' => 'wrc', 'type' => 'applications', 'id' => $params->get('app_id') . '/new-pitch']);// Lets see if there is a post to procces

        if ($request->isMethod('POST')) {

                $resource = $request->request->all();

                if($resource = $commonGroundService->saveResource($resource, ['component' => 'chrc', 'type' => 'pitches'])){
                    $id = $resource['id'];
                    $this->redirect('app_tender_pitch', $id);
                }

//                if (key_exists('@component', $resource)) {
//                    // Passing the variables to the resource
//                    $configuration = $commonGroundService->saveResource($resource, ['component' => $resource['@component'], 'type' => $resource['@type']]);
//                }
        }

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

    /**
     * @Route("/pitches/{id}")
     * @Template
     */
    public function pitchAction(Session $session, Request $request, ApplicationService $applicationService, CommonGroundService $commonGroundService, ParameterBagInterface $params, $id)
    {
        $content = false;
        $variables = $applicationService->getVariables();

        // Lets provide this data to the template
        $variables['query'] = $request->query->all();
        $variables['post'] = $request->request->all();

        // Lets find an appoptiate slug
        $template = $commonGroundService->getResource(['component' => 'wrc', 'type' => 'applications', 'id' => $params->get('app_id') . '/pitch']);// Lets see if there is a post to procces

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
        $variables['comments'] = $commonGroundService->getResource(['component' => 'rc', 'type' => 'reviews', 'resource' => $variables['resource']['@id']]);

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

    /**
     * @Route("/challenges/{id}")
     * @Template
     */
    public function challengeAction(Session $session, Request $request, ApplicationService $applicationService, CommonGroundService $commonGroundService, ParameterBagInterface $params, $id)
    {
        $content = false;
        $variables = $applicationService->getVariables();

        // Lets provide this data to the template
        $variables['query'] = $request->query->all();
        $variables['post'] = $request->request->all();

        // Lets find an appoptiate slug
        $template = $commonGroundService->getResource(['component' => 'wrc', 'type' => 'applications', 'id' => $params->get('app_id') . '/challenge']);
        $variables['resource'] = $commonGroundService->getResource(['component' => 'chrc', 'type' => 'tenders', 'id' => $id]);

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

    /**
     * @Route("/proposals/{id}")
     * @Template
     */
    public function proposalAction(Session $session, Request $request, ApplicationService $applicationService, CommonGroundService $commonGroundService, ParameterBagInterface $params, $id)
    {
        $content = false;
        $variables = $applicationService->getVariables();

        // Lets provide this data to the template
        $variables['query'] = $request->query->all();
        $variables['post'] = $request->request->all();

        // Lets find an appoptiate slug
        $template = $commonGroundService->getResource(['component' => 'wrc', 'type' => 'applications', 'id' => $params->get('app_id') . '/proposal']);
        $variables['resource'] = $commonGroundService->getResource(['component' => 'chrc', 'type' => 'proposals', 'id' => $id]);

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


    /**
     * @Route("/deals/{id}")
     * @Template
     */
    public function dealAction(Session $session, Request $request, ApplicationService $applicationService, CommonGroundService $commonGroundService, ParameterBagInterface $params, $id)
    {
        $content = false;
        $variables = $applicationService->getVariables();

        // Lets provide this data to the template
        $variables['query'] = $request->query->all();
        $variables['post'] = $request->request->all();

        // Lets find an appoptiate slug
        $template = $commonGroundService->getResource(['component' => 'wrc', 'type' => 'applications', 'id' => $params->get('app_id') . '/deal']);
        $variables['resource'] = $commonGroundService->getResource(['component' => 'chrc', 'type' => 'deals', 'id' => $id]);

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

    /**
     * @Route("/questions/{id}")
     * @Template
     */
    public function questionAction(Session $session, Request $request, ApplicationService $applicationService, CommonGroundService $commonGroundService, ParameterBagInterface $params, $id)
    {
        $content = false;
        $variables = $applicationService->getVariables();

        // Lets provide this data to the template
        $variables['query'] = $request->query->all();
        $variables['post'] = $request->request->all();

        // Lets find an appoptiate slug
        $template = $commonGroundService->getResource(['component' => 'wrc', 'type' => 'applications', 'id' => $params->get('app_id') . '/question']);
        $variables['resource'] = $commonGroundService->getResource(['component' => 'chrc', 'type' => 'questions', 'id' => $id]);

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






