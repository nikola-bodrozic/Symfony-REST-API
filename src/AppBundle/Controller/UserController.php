<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\User;

class UserController extends FOSRestController {

    /**
     * @Rest\Get("/user")
     */
    public function getAction() {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
        if ($restresult === null) {
            return new View("there are no users exist", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/user/{id}")
     */
    public function idAction($id) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if ($singleresult === null) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }
    
    /**
     * Get bearer token by POST form 
     * 
     * @Route("/login")
     * @param Request $request
     * @return View
     */
    public function login(Request $request) {
         $n = $request->request->get('name');
         $p = $request->request->get('password');
         $t = $request->request->get('csrf_token');
         if ($n == 'test' && $p == '123' && $t == 'abc') {
            return $this->json(["token" => "e4d3"]);
         }
         return new View('Wrong credentials', Response::HTTP_FORBIDDEN);
    }
    
    /**
     * Check if user has proper token
     * 
     * @param type $param
     * @return boolean
     */
    private function is($param) {
        if ($param != 'Bearer e4d3') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @Rest\Post("/user")
     */
    public function postAction(Request $request) {
        $h = $request->headers->get('Authorization');
        if ($this->is($h)) {
            return new View("not authorisaed", Response::HTTP_UNAUTHORIZED);
        }
        //else {
        $data = new User;
        $name = $request->get('name');
        $role = $request->get('role');
        if (empty($name) || empty($role)) {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setName($name);
        $data->setRole($role);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        return $this->json(['id' => $data->getId()]);
        //}
    }

    /**
     * @Rest\Put("/user/{id}")
     */
    public function updateAction($id, Request $request) {
        $h = $request->headers->get('Authorization');
        if ($this->is($h)) {
            return new View("not authorisaed", Response::HTTP_UNAUTHORIZED);
        }        
        $data = new User;
        $name = $request->get('name');
        $role = $request->get('role');
        $sn = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if (empty($user)) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        } elseif (!empty($name) && !empty($role)) {
            $user->setName($name);
            $user->setRole($role);
            $sn->flush();
            return new View("User Updated Successfully", Response::HTTP_OK);
        } elseif (empty($name) && !empty($role)) {
            $user->setRole($role);
            $sn->flush();
            return new View("role Updated Successfully", Response::HTTP_OK);
        } elseif (!empty($name) && empty($role)) {
            $user->setName($name);
            $sn->flush();
            return new View("User Name Updated Successfully", Response::HTTP_OK);
        } else
            return new View("User name or role cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @Rest\Delete("/user/{id}")
     */
    public function deleteAction($id, Request $request) {
        $h = $request->headers->get('Authorization');
        if ($this->is($h)) {
            return new View("not authorisaed", Response::HTTP_UNAUTHORIZED);
        }        
        $data = new User;
        $sn = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if (empty($user)) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        } else {
            $sn->remove($user);
            $sn->flush();
        }
        return new View("deleted successfully", Response::HTTP_OK);
    }

}
