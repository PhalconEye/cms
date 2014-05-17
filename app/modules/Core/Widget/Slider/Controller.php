<?php
/*
  +------------------------------------------------------------------------+
  | PhalconEye CMS                                                         |
  +------------------------------------------------------------------------+
  | Copyright (c) 2013-2014 PhalconEye Team (http://phalconeye.com/)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file LICENSE.txt.                             |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconeye.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
*/

namespace Core\Widget\Slider;

use Core\Form\CoreForm;
use Engine\Widget\Controller as WidgetController;

/**
 * Slider widget controller.
 *
 * @category  PhalconEye
 * @package   Core\Widget\Slider
 * @author    Piotr Gasiorowski <p.gasiorowski@vipserv.org>
 * @copyright 2013-2014 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 */
class Controller extends WidgetController
{
    const
        /**
         * Default number of slides
         */
        DEFAULT_SLIDES = 4,

        /**
         * Default duration between slides (ms)
         */
        DEFAULT_DURATION = 5000,

        /**
         * Default spped of slides  (ms)
         */
        DEFAULT_SPEED = 500;

    /**
     * Index action.
     *
     * @return void
     */
    public function indexAction()
    {
        $params = $this->getAllParams();
        $slides = [];
        $maxSlides = (int) $params['qty']? (int) $params['qty'] : self::DEFAULT_SLIDES;

        // Slider params
        $sliderParams = [
            'duration'   => (int) $params['duration'],
            'speed'      => (int) $params['speed'],
            'auto'       => (int) $params['auto'],
            'auto_hover' => (int) $params['auto_hover'],
            'controls'   => (int) $params['controls'],
            'video'      => (int) $params['video'],
            'pager'      => (int) $params['pager']
        ];

        // Set slides
        $i = 0;
        foreach ($params as $name => $value) {
            if ($i < $maxSlides && strpos($name, 'slide') === 0 && !empty($value)) {
                $slides[] = $value;
                $i++;
            }
        }

        // Assets
        $assets = $this->getDi()->get('assets');
        $assets->addCss('external/bxslider-4/jquery.bxslider.css');

        if ($sliderParams['video']) {
            $assets->addJs('external/bxslider-4/plugins/jquery.fitvids.js');
        }
        $assets->addJs('external/bxslider-4/jquery.bxslider.js');

        // View parameters
        $this->view->title     = isset($params['title'])? $params['title'] : '';
        $this->view->baseUrl   = $this->getDI()->get('config')->application->baseUrl;
        $this->view->height    = (int) $params['height'];
        $this->view->slider_id = (int) $params['content_id'];
        $this->view->params    = $sliderParams;
        $this->view->slides    = $slides;
    }

    /**
     * Action for management from admin panel.
     *
     * @return CoreForm
     */
    public function adminAction()
    {
        $nrOfSlides = $this->getParam('qty', self::DEFAULT_SLIDES);
        $selectOptions = array_combine(range(3, 10), range(3, 10));
        $editorOptions =  [
            'toolbar' => [[ 'Source', '-', 'Bold', 'Italic', '-', 'Link', 'Image' ]],
            'allowedContent' => true,
        ];

        $form = new CoreForm();

        // Dynamic number of slides
        $fieldSet = $form->addContentFieldSet('Slides');
        $fieldSet->addSelect('qty', 'Number of slides', 'Save to take effect', $selectOptions, self::DEFAULT_SLIDES);

        for ($i=1; $i <= $nrOfSlides; $i++) {
            $fieldSet->addCkEditor("slide$i", "Slide $i HTML", '', [], null, ['elementOptions' => $editorOptions ]);
        }

        // Advanced params
        $form->addContentFieldSet('Advanced')
             ->addText('height', 'Height', 'Force height of the slider (in px)')
             ->addText('duration', 'Duration', 'Duration between slides (in ms)', self::DEFAULT_DURATION)
             ->addText('speed', 'Speed', 'Spped of slides (in ms)', self::DEFAULT_SPEED)
             ->addCheckbox('auto', 'Auto', 'Slides will automatically transition', 1, true)
             ->addCheckbox('auto_hover', 'Auto Hover', 'Auto show will pause when mouse hovers over slider', 1, true)
             ->addCheckbox('controls', 'Controls', 'Next and Prev controls will be added', 1, true)
             ->addCheckbox('video', 'Has video', 'You will need this if any slides contain video', 1, false)
             ->addCheckbox('pager', 'Pager', 'Pager will be added', 1, true);

        $form->addHtml('separator');

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function isCached()
    {
        return true;
    }
}