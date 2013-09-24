<?php

/**
 * PhalconEye
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to phalconeye@gmail.com so we can send you a copy immediately.
 *
 */

namespace Core\Widget\HtmlBlock;

class Controller extends \Engine\Widget\Controller
{

    public function indexAction()
    {
        $this->view->title = $this->getParam('title');
        $currentLocale = $this->session->get('locale', 'en');
        $defaultLocale = \Core\Model\Settings::getSetting('system_default_language', 'en');

        $html = $this->getParam('html_'.$currentLocale);
        if (empty($html)){
            // let's look at default language html
            $html = $this->getParam('html_'.$defaultLocale);
            if (empty($html))
                return $this->setNoRender();
        }

        $this->view->html = $html;
    }

    public function adminAction()
    {
        $form = new \Engine\Form();

        $form->addElement('text', 'title', array(
            'label' => 'Title'
        ));

        // adding additional html for language selector support
        $languageSelectorHtml = '
                <style type="text/css">
                    form .form_elements > div{
                        min-height: auto !important;
                        padding-top: 0px !important;
                    }

                    form .form_elements > div:nth-last-child(2){
                        padding-top: 10px !important;
                    }
                </style>
                <script type="text/javascript">
                     var defaultLocale = "%s";
                     $(document).ready(function(){
                        $("#html_block_language").change(function(){
                            $("#cke_html_"+$(this).val()).show();
                            $(".cke").not("#cke_html_"+$(this).val()).hide();
                        });

                        // hide inactive
                        $("textarea").not("#html_"+defaultLocale).hide();

                        setTimeout(function(){
                            %s
                            setTimeout(function(){$(".cke").not("#cke_html_"+defaultLocale).hide();}, 200);
                        }, 200);
                    });
                </script>
                <div style="margin-bottom: -65px;float: left;">
                    <div class="form_label">
                        <label for="title">%s</label>
                        <select id="html_block_language" style="width: 120px;">
                        %s
                        </select>
                    </div>
                </div>
                ';

        // creating languages boxes
        $languages = \Core\Model\Language::find();
        $languageHtmlItems = '';
        $languageTextCode = '';
        $defaultLocale = \Core\Model\Settings::getSetting('system_default_language', 'en');

        $order = 3; // all textarea's must be ordered together
        foreach($languages as $language){
            $selectedLanguage = '';
            if ($language->locale == $defaultLocale){
                $selectedLanguage = 'selected="selected"';
            }

            $form->addElement('textArea', 'html_'.$language->locale, array(), $order++);
            $languageTextCode .= 'CKEDITOR.replace("html_'.$language->locale.'");';
            $languageHtmlItems .= '<option '.$selectedLanguage.' value='.$language->locale.'>'.$language->name.'</option>';
        }

        $languageSelectorHtml = sprintf($languageSelectorHtml, $defaultLocale, $languageTextCode, $this->di->get('trans')->_('HTML block, for:'), $languageHtmlItems);

        // adding created html to form
        $form->addElement('html', 'html', array(
            'ignore' => true,
            'html' => $languageSelectorHtml
        ));


        return $form;
    }

    public function isCached()
    {
        return true;
    }

}