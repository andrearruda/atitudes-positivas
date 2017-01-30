<?php
namespace Application\Controller;

use Application\Form\MessageForm;
use Application\Service\MessageService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Thapp\XmlBuilder\XMLBuilder;
use Thapp\XmlBuilder\Normalizer;

class MessageController extends AbstractActionController
{
    protected $entityManager;

    public function indexAction()
    {
        $service_message = new MessageService($this->getEntityManager());
        $entities = $service_message->findAll();

        $page = $this->params()->fromRoute('page');
        $paginator = new Paginator(new ArrayAdapter($entities));
        $paginator->setCurrentPageNumber($page);

        $viewModel = new ViewModel(array(
            'data' => $paginator,
            'page' => $page
        ));

        return $viewModel;
    }

    public function showAction()
    {
        $id = $this->params()->fromRoute('id');
        $service_message = new MessageService($this->getEntityManager());

        $entity = $service_message->findById($id);

        $viewModel = new ViewModel(array(
            'entity' => $entity
        ));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id');

        $form = new MessageForm($this->getEntityManager());
        $service_message = new MessageService($this->getEntityManager());
        $entity = $service_message->findById($id);

        $data_form = array(
            'fieldset_staff' => array(
                'id_unit' => $entity->getStaff()->getUnit()->getId(),
                'name' => $entity->getStaff()->getName(),
                'email' => $entity->getStaff()->getEmail(),
                'departament' => $entity->getStaff()->getDepartament()
            ),
            'fieldset_message' => array(
                'description' => $entity->getDescription(),
            )
        );

        $form->setData($data_form);
        $form->get('fieldset_message')->get('image')->setAttribute('required', false);
        $form->getInputFilter()->get('fieldset_message')->remove('image');
        $form->get('fieldset_message')->remove('image');

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $data_post = array(
                'csrf' => $this->getRequest()->getPost('csrf'),
                'fieldset_staff' => array(
                    'id_unit' => $this->getRequest()->getPost('fieldset_staff')['id_unit'],
                    'name' => $this->getRequest()->getPost('fieldset_staff')['name'],
                    'email' => $this->getRequest()->getPost('fieldset_staff')['email'],
                    'departament' => $this->getRequest()->getPost('fieldset_staff')['departament'],
                ),
                'fieldset_message' => array(
                    'description' => $this->getRequest()->getPost('fieldset_message')['description'],
                    'image' => $this->getRequest()->getFiles('fieldset_message')['image'],
                )
            );

            $form->setData($data_post);
            if($form->isValid())
            {
                $data_form = $form->getData();

                $service_message = new MessageService($this->getEntityManager());
                $service_message->update($data_form, $id);

                return $this->redirect()->toRoute('message');
            }
            else
            {
                $data_form = $form->getData();
                if(@file_exists($data_form['fieldset_message']['image']['tmp_name']))
                {
                    unlink($data_form['fieldset_message']['image']['tmp_name']);
                }
            }
        }

        return array(
            'form' => $form
        );
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        $service_message = new MessageService($this->getEntityManager());

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $service_message->delete($id);

            $data = array(
                'id' => $id
            );

            $jsonModel = new JsonModel($data);

            return $jsonModel;
        }
        else
        {
            $entity = $service_message->findById($id);

            $viewModel = new ViewModel(array(
                'entity' => $entity
            ));
            $viewModel->setTerminal(true);
            return $viewModel;
        }
    }

    public function activeAction()
    {
        $id = $this->params()->fromRoute('id');
        $data = array(
            'active' => $this->params()->fromQuery('active')
        );

        $service_message = new MessageService($this->getEntityManager());
        $message = $service_message->active($data, $id);

        $hydrator = new DoctrineHydrator($this->getEntityManager());
        $data = $hydrator->extract($message);

        $jsonModel = new JsonModel($data);

        return $jsonModel;
    }

    public function listAction()
    {
        $hydrator = new DoctrineHydrator($this->getEntityManager());
        $service_message = new MessageService($this->getEntityManager());
        $messages = $service_message->findByActive();

        $data = array();

        foreach($messages as $key => $message)
        {
            $data[$key] = $hydrator->extract($message);
            $data[$key]['staff'] = $hydrator->extract($message->getStaff());
            $data[$key]['staff']['unit'] = $hydrator->extract($message->getStaff()->getUnit());

            $data[$key]['image'] = 'http://' . $this->getRequest()->getServer('HTTP_HOST') . '/upload/images/middle/' . $data[$key]['image'];

            unset(
                $data[$key]['id'],
                $data[$key]['createdAt'], $data[$key]['updatedAt'], $data[$key]['deletedAt'],
                $data[$key]['active']
            );

            unset(
                $data[$key]['staff']['id'],
                $data[$key]['staff']['createdAt'], $data[$key]['staff']['updatedAt'], $data[$key]['staff']['deletedAt']
            );

            unset(
                $data[$key]['staff']['unit']['id'],
                $data[$key]['staff']['unit']['createdAt'], $data[$key]['staff']['unit']['updatedAt'], $data[$key]['staff']['unit']['deletedAt']
            );
        }

        $xmlBuilder = new XmlBuilder('root');
        $xmlBuilder->setSingularizer(function ($name) {
            if ('matches' === $name) {
                return 'match';
            }
            if ('teams' === $name) {
                return 'team';
            }
            if ('keys' === $name) {
                return 'key';
            }
            return $name;
        });
        $xmlBuilder->load($data);
        $xml_output = $xmlBuilder->createXML(true);

        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Content-Type', 'text/xml; charset=utf-8');
        $response->setContent($xml_output);

        return $response;
    }

    /**
     * @return mixed
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param mixed $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }
}