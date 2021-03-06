<?php

namespace TwitterBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/tweets")
 */
class TwitterController extends Controller
{
    /**
     * @Route("/{username}", name="tweets", defaults = {"username" = ""})
     * 
     * @Method("GET")
     */
    public function indexAction(Request $request, $username = "")
    {
      $limit = $request->get("limit");
      
      $response = new Response();
      $response->headers->set('Content-Type', 'application/json');

      if (empty($username)) {
        $content = ["message" => "Username required"];
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
      }
      else {
        $twitter = $this->get('twitter_service');

        try {
          $content = $twitter->getTweets($username, $limit);
        }
        catch (\Exception $ex) {
          $content = ["message" => $ex->getMessage()];             
          $response->setStatusCode($ex->getCode());
        }
      }

      $response->setContent(\json_encode($content));

      return $response;
    }
}
