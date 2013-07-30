<?php

namespace NZZ\MyTownBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BasePeer;
use Symfony\Component\Validator\Tests\Constraints\CallbackValidatorTest_Class;

use NZZ\MyTownBundle\Model\ProjectQuery;
use NZZ\MyTownBundle\Model\Project;
use NZZ\MyTownBundle\Model\ProjectPeer;

class AdminController extends Controller
{
    public function indexAction()
    {
        $projects  = ProjectQuery::create()
            ->find()->toArray(null,false,BasePeer::TYPE_FIELDNAME);
        $projectsFields = ProjectPeer::getFieldNames(BasePeer::TYPE_FIELDNAME);
        return $this->render('NZZMyTownBundle:Admin:index.html.twig', array(
                'fields' => $projectsFields,
                'projects' => $projects
            )
        );
    }

    public function addAction()
    {
//        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
//            return $this->redirect($this->generateUrl('vfe_emotion_homepage'));
//        }
        $project = new Project();
        $form = $this->createFormBuilder($project)
            ->add('name', 'text', array('required' => true))
            ->add('shortname','text', array('required' => true))
            ->add('centerlatitude','text', array('required' => true))
            ->add('centerlatitude', 'text', array('required' => true))
            ->add('centerlongitude', 'text', array('required' => true))
            ->add('defaultzoom', 'text', array('required' => true))
            ->add('language', 'choice', array('required' => true,
                    'choices'   => array('en' => 'English', 'fr' => 'French', 'de' => 'Deutsch')))
            ->add('save', 'submit')
            ->getForm();

        return $this->render('NZZMyTownBundle:Admin:add.html.twig', array(
                'form' => $form->createView()
            )
        );
    }

    public function saveAction()
    {
        $data = $this->getRequest()->get('form');
        if (!empty($data['id'])) {
            $project = ProjectQuery::create()->findOneById($data['id']);
        } else {
            $project  = new Project();
        }
        $project->setName($data['name']);
        $project->setShortname($data['shortname']);
        $project->setCenterlatitude($data['centerlatitude']);
        $project->setCenterlongitude($data['centerlongitude']);
        $project->setDefaultzoom($data['defaultzoom']);
        $project->setLanguage($data['language']);
        $project->save();

        return $this->redirect($this->generateUrl('nzz_my_town_admin_dashboard'));
    }


    public function editProjectAction($projectId)
    {
        $project = ProjectQuery::create()->findOneById($projectId);
        $form = $this->createFormBuilder($project)
            ->add('id','text',array('read_only' => true))
            ->add('name', 'text', array('required' => true))
            ->add('shortname', 'text', array('required' => true))
            ->add('centerlatitude', 'text', array('required' => true))
            ->add('centerlatitude', 'text', array('required' => true))
            ->add('centerlongitude', 'text', array('required' => true))
            ->add('defaultzoom', 'text', array('required' => true))
            ->add('language', 'choice', array('required' => true,
                    'choices'   => array('en' => 'English', 'fr' => 'French', 'de' => 'Deutsch')))
            ->add('save', 'submit')
            ->getForm();

        return $this->render('NZZMyTownBundle:Admin:editProject.html.twig', array(
                'form' => $form->createView()
            )
        );
    }

    public function removeAction($projectId)
    {
        return $this->render('NZZMyTownBundle:Admin:index.html.twig', array(
            )
        );
    }
}
