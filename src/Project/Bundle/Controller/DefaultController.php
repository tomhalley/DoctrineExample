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
    public function deletePostAction($id) 
    {
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

        return $this->redirect($this->generateUrl('project_edit_posts'));
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
    public function editAction(Request $request, $id = null) {
        if($id) {
            $post = $this->getDoctrine()
                ->getRepository("ProjectBundle:Post")
                ->find($id);

            if(!$post) {
                $post = new Post();
            }
        } else {
            $post = new Post();
        }

        $form = $this->createFormBuilder($post)
            ->add('title', 'text')
            ->add('post', 'textarea')
            ->add('author', 'text')
            ->getForm();

        if ($request->isMethod('POST')) {
            if($id) {
                $post = $this->getDoctrine()
                    ->getRepository("ProjectBundle:Post")
                    ->find($id);
            }

            $form->bind($request);

            if($form->isValid()) {
                $post->setDate(new \DateTime('now'));

                $em = $this->getDoctrine()->getmanager();
                $em->persist($post);
                $em->flush();
            }

            return $this->redirect($this->generateUrl('project_edit_posts'));
        }

        return $this->render('ProjectBundle:Default:edit.html.twig', array(
            'form' => $form->createView(),
            'post' => $post
        ));
    }
}

?>