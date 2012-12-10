<?php

namespace Project\Bundle\Controller;

use Project\Bundle\Entity\Post;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Template
     */
    public function indexAction() 
    {
        $posts = $this->getDoctrine()
            ->getRepository("ProjectBundle:Post")
            ->findAll();

        return array('posts' => $posts);
    }

    /**
     * @Template
     */
    public function editPostAction($id) 
    {
        $post = $this->getDoctrine()
            ->getRepository("ProjectBundle:Post")
            ->find($id);

        if(!$post) {
            throw $this->createNotFoundException(
                "No Post found with ID of " . $id
            );
        }

        return array("post" => $post);
    }
    /**
     * @Template
     */
    public function deletePostAction($id) {
        $post = $this->getDoctrine()
            ->getRepository("ProjectBundle:Post")
            ->find($id);

        if(!$post) {
            throw $this->createNotFoundException(
                "No Post found with ID of " . $id
            );
        }

        $em = $this->getDoctrine()->getmanager();
        $em->remove($post);
        $em->flush();

        return new Response("Post with ID of ".$id." has been deleted");
    }

    /**
     * @Template
     */
    public function viewAllAction()
    {
        $posts = $this->getDoctrine()
            ->getRepository("ProjectBundle:Post")
            ->findAll();

        return array('posts' => $posts);
    }

    /**
     * @Template
     */
    public function postAction($id)
    {
    	$post = $this->getDoctrine()
    		->getRepository("ProjectBundle:Post")
    		->find($id);

    	if(!$post) {
    		throw $this->createNotFoundException(
    			"No product found for id " . $id
    		);
    	}

        return array("post" => $post);
    }

    /**
     * @Template
     */
    public function newPostAction() {
        return array();
    }

    /**
     * @Template
     */
    public function submitPostAction(Request $request)
    {
        $post = new Post();

        if($request->isMethod('POST'))
        {
            $post->setTitle($request->request->get("title"));
            $post->setPost($request->request->get("post"));
            $post->setDate(new \DateTime("now"));
            $post->setAuthor($request->request->get("author"));

            $em = $this->getDoctrine()->getmanager();
            $em->persist($post);
            $em->flush();
        }

        return new Response ("Created post id " . $post->getId());
    }
}

?>