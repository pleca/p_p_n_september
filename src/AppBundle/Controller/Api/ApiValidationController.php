<?php

namespace AppBundle\Controller\Api;

use AppBundle\Controller\Api\ApiProblem;
use AppBundle\Controller\Api\ApiProblemException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;

class ApiValidationController extends Controller
{
    protected function checkForErrors(ConstraintViolationList $err)
    {
        $errors = array();
        $errorsI = $err->getIterator();

        while ($errorsI->valid()) {
            $f = $errorsI->current();
            $errors[$f->getPropertyPath()] = $f->getMessage();
            $errorsI->next();
        }

        if (count($errors) > 0) {
            $this->throwApiProblemValidationException($errors);
        }
    }

    protected function validateInput($input)
    {
        if ($input === null) {
            $apiProblem = new ApiProblem(400, ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT);

            throw new ApiProblemException($apiProblem);
        }
    }

    protected function throwApiProblemValidationException($errors)
    {
        $apiProblem = new ApiProblem(
            400,
            ApiProblem::TYPE_VALIDATION_ERROR
        );
        $apiProblem->set('errors', $errors);

        throw new ApiProblemException($apiProblem);
    }
}