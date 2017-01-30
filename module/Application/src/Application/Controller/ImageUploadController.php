<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class ImageUploadController extends AbstractActionController
{
    public function indexAction()
    {
        $type = $this->params()->fromRoute('type');
        $file = $this->params()->fromRoute('file');

        $path = __DIR__ . '/../../../../../data/uploads/' . $type . '/' . $file;

        $response = $this->getResponse();
        if(file_exists($path))
        {
            $output = file_get_contents($path);

            $response->setContent($output);
            $response->getHeaders()->addHeaderLine('Content-Type', 'image/png');
        }
        else
        {
            $response->setStatusCode(404);
        }

        return $response;
    }
}