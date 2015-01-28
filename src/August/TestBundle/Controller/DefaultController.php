<?php

namespace August\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints as Assert;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        // Check for key/value pairs and save to the session
        $vars = $request->query->all();
        if (count($vars)) {
            foreach($vars as $key => $val) {
                $request->getSession()->set($key, $val);
            }
            return $this->redirect('/');
        }

        return array(
            'hostname' => \gethostname(),
        );
    }

    /**
     * @Route("/media/")
     * @Template()
     */
    public function mediaAction(Request $request)
    {
        // Get the AWS S3 filesystem
        $filesystem = $this->get('gaufrette.media_files_filesystem');

        // Build a form
        $defaultData = array('attachment' => '');
        $form = $this->createFormBuilder($defaultData)
            ->add('attachment', 'file', array(
                'constraints' => array(
                    new Assert\Image(array(
                        'minWidth' => 100,
                        'maxWidth' => 500,
                        'minHeight' => 100,
                        'maxHeight' => 500,
                    ))
                ),
            ))
            ->getForm();

        // Handle the request
        $form->handleRequest($request);
        if ($form->isValid()) {

            // Save the file
            $data = $form->getData();

            if ($filesystem->has($data['attachment']->getClientOriginalName())) {
                $filesystem->delete($data['attachment']->getClientOriginalName());
            }
            $filesystem->write($data['attachment']->getClientOriginalName(), file_get_contents($data['attachment']->getPathname()));

            return $this->redirect('/media');

        }

        $files = $filesystem->listKeys();
        foreach($files as $key => $file) {
            if (stristr($file,'/')) {
                unset($files[$key]);
            }
        }

        return array(
            'hostname' => \gethostname(),
            'form' => $form->createView(),
            'files' => $files
        );
    }


    /**
     * @Route("/secure")
     * @Template()
     */
    public function secureAction(Request $request)
    {
        return array(
            'hostname' => \gethostname(),
        );
    }
}
