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

namespace Core\Controller;

/**
 * @RoutePrefix("/admin/pages", name="admin-pages")
 */
class AdminPagesController extends \Core\Controller\BaseAdmin
{
    public function init()
    {
        $navigation = new \Engine\Navigation();
        $navigation
            ->setItems(array(
                'index' => array(
                    'href' => 'admin/pages',
                    'title' => 'Browse',
                    'prepend' => '<i class="icon-list icon-white"></i>'
                ),
                1 => array(
                    'href' => 'javascript:;',
                    'title' => '|'
                ),
                'create' => array(
                    'href' => 'admin/pages/create',
                    'title' => 'Create new page',
                    'prepend' => '<i class="icon-plus-sign icon-white"></i>'
                )));

        $this->view->navigation = $navigation;
    }

    /**
     * @Get("/", name="admin-pages")
     */
    public function indexAction()
    {
        // index page logic
        $currentPage = $this->request->getQuery('page', 'int', 1);
        if ($currentPage < 1) $currentPage = 1;

        $builder = $this->modelsManager->createBuilder()
            ->from('\Core\Model\Page');

        $paginator = new \Phalcon\Paginator\Adapter\QueryBuilder(
            array(
                "builder" => $builder,
                "limit" => 25,
                "page" => $currentPage
            )
        );

        // Get the paginated results
        $page = $paginator->getPaginate();

        $this->view->paginator = $page;
    }

    /**
     * @Route("/create", methods={"GET", "POST"}, name="admin-pages-create")
     */
    public function createAction()
    {
        $form = new \Core\Form\Admin\Page\Create();
        $this->view->form = $form;

        if (!$this->request->isPost() || !$form->isValid($_POST)) {
            return;
        }

        $page = $form->getValues();
        $url = $page->getUrl();
        if (!empty($url)) {
            $page->setUrl(str_replace('/', '', str_replace('\\', '', $url)));
        }

        $page->save();
        $this->flashSession->success('New object created successfully!');
        return $this->response->redirect(array('for' => "admin-pages-manage", 'id' => $form->getValues()->getId()));
    }

    /**
     * @Route("/edit/{id:[0-9]+}", methods={"GET", "POST"}, name="admin-pages-edit")
     */
    public function editAction($id)
    {
        $page = null;
        if ($id)
            $page = \Core\Model\Page::findFirst($id);
        if (!$page)
            return $this->response->redirect(array('for' => "admin-pages"));


        $form = new \Core\Form\Admin\Page\Edit($page);
        $this->view->form = $form;

        if (!$this->request->isPost() || !$form->isValid($_POST)) {
            return;
        }

        $page = $form->getValues();
        $url = $page->getUrl();
        if (!empty($url) && $url != '/') {
            $page->setUrl(str_replace('/', '', str_replace('\\', '', $url)));
        }

        $roles = $this->request->get('roles');
        if ($roles == null) {
            $page->setRoles(array());
        }

        $page->save();
        $this->flashSession->success('Object saved!');
        return $this->response->redirect(array('for' => "admin-pages"));
    }

    /**
     * @Get("/delete/{id:[0-9]+}", name="admin-pages-delete")
     */
    public function deleteAction($id)
    {
        $page = null;
        if ($id)
            $page = \Core\Model\Page::findFirst($id);
        if ($page) {
            $page->delete();
            $this->flashSession->notice('Object deleted!');
        }

        return $this->response->redirect(array('for' => "admin-pages"));
    }

    /**
     * @Get("/manage/{id:[0-9]+}", name="admin-pages-manage")
     */
    public function manageAction($id)
    {
        $page = null;
        if ($id)
            $page = \Core\Model\Page::find($id)->getFirst();
        if (!$page)
            return $this->response->redirect(array('for' => "admin-pages"));

        // Collecting widgets info
        $content = \Core\Model\Widget::find();
        $modulesDefinition = $this->getDI()->get('modules');
        $modules = array();
        foreach($modulesDefinition as $module => $enabled){
            if (!$enabled) continue;
            $modules[$module] = ucfirst($module);
        }
        $bundlesWidgetsMetadata = array();
        foreach ($content as $widget) {
            $bundlesWidgetsMetadata[$modules[$widget->getModule()]][$widget->getId()] = array(
                'widget_id' => $widget->getId(),
                'title' => $widget->getTitle(),
                'description' => $widget->getDescription(),
                'name' => $widget->getName()
            );
        }

        //Creating Widgets List data
        $widgetsListData = array();
        foreach ($bundlesWidgetsMetadata as $key => $widgetsMeta) {
            foreach ($widgetsMeta as $wId => $wMeta) {
                $widgetsListData[$wId] = $wMeta;
                $widgetsListData[$wId]["name"] = $widgetsMeta[$wId]["name"] = $wMeta['name'];
                $widgetsListData[$wId]["widget_id"] = $widgetsMeta[$wId]["widget_id"] = $wId;
                unset($widgetsListData[$wId]['adminAction']); // this throw exception in parseJSON

            }
            $bundlesWidgetsMetadata[$key] = $widgetsMeta;
        }

        $content = $page->getWidgets(false);
        $currentPageWidgets = array();
        $widgetIndex = 0;
        foreach ($content as $widget){
            $currentPageWidgets[$widgetIndex] = array(
                'widget_index' => $widgetIndex, // indification for this array
                'id' => $widget->getId(),
                'layout' => $widget->getLayout(),
                'widget_id' => $widget->getWidgetId(),
                'params' => $widget->getParams()
            );
            $widgetIndex++;
        }


        // store parameters in session
        $this->session->set('admin-pages-manage', $currentPageWidgets);
        $this->session->set('admin-pages-widget-index', $widgetIndex);

        $this->view->currentPage = $page;
        $this->view->bundlesWidgetsMetadata = json_encode($bundlesWidgetsMetadata);
        $this->view->widgetsListData = json_encode($widgetsListData);
        $this->view->currentPageWidgets = json_encode($currentPageWidgets);
    }

    /**
     * @Route("/widget-options", methods={"GET", "POST"}, name="admin-pages-widget-options")
     */
    public function widgetOptionsAction()
    {
        $widgetIndex = $this->request->get('widget_index', 'int', -1);
        if ($widgetIndex != '0' && intval($widgetIndex) == 0)
            $widgetIndex = -1;
        $currentPageWidgets = $this->session->get('admin-pages-manage', array());

        if ($widgetIndex == -1){
            $widgetIndex = $this->session->get('admin-pages-widget-index');
            $currentPageWidgets[$widgetIndex] =array(
                'widget_index' => $widgetIndex, // indification for this array
                'id' => 0,
                'layout' => $this->request->get('layout', 'string', 'middle'),
                'widget_id' =>$this->request->get('widget_id', 'int'),
                'params' => array()
            );

        }

        if (empty($currentPageWidgets[$widgetIndex]))
            return;

        $widgetData = $currentPageWidgets[$widgetIndex];

        $id = $widgetData['id'];
        $widgetParams = $widgetData['params'];
        $widget_id = $widgetData['widget_id'];
        $widgetMetadata = \Core\Model\Widget::findFirst('id = ' . $widget_id);
        $form = new \Engine\Form();

        // building widget form
        $adminForm = $widgetMetadata->getAdminForm();
        if (empty($adminForm)) {
            $form->addElement('text', 'title', array(
                'label' => 'Title'
            ));
        } elseif ($adminForm == 'action') {
            $widgetName = $widgetMetadata->getName();
            $widgetClass = '\Core\Widget\\'.$widgetName.'\Controller';
            $widgetObject = new $widgetClass();
            $widgetObject->initialize();
            $form = call_user_func_array(array($widgetObject, "adminAction"), $_REQUEST);
        } else {
            $form = new $adminForm();
        }

        if ($widgetMetadata->getIsPaginated() == 1) {
            $form->addElement('text', 'count', array(
                'label' => 'Items count',
                'value' => 10
            ), 10000);
        }

        if ($widgetMetadata->getIsAclControlled() == 1) {
            $form->addElement('select', 'roles', array(
                'label' => 'Roles',
                'options' => \User\Model\Role::find(),
                'using' => array('id', 'name'),
                'multiple' => 'multiple'
            ), 10000);

        }

        // set form values
        if (!empty($widgetParams))
            $form->setValues($widgetParams);

        if (!$this->request->isPost() || !$form->isValid($_POST)) {
            $this->view->form = $form;
            $this->view->id = $id;
            $this->view->name = $widgetMetadata->getName();

            return;
        }

        $currentPageWidgets[$widgetIndex]['params'] = $form->getValues();
        $this->view->widget_index = $widgetIndex;


        $this->view->form = $form;
        $this->view->id = $id;
        $this->view->name = $widgetMetadata->getName();

        $this->session->set('admin-pages-manage', $currentPageWidgets);
        $this->session->set('admin-pages-widget-index', ++$widgetIndex);
    }

    /**
     * @Route("/save-layout/{id:[0-9]+}", methods={"POST"}, name="admin-pages-save-layout")
     */
    public function saveLayoutAction($id)
    {
        $response = new \Phalcon\Http\Response();
        $response->setStatusCode(200, "OK");
        $response->setContent(json_encode(array("error" => 0)));

        $layout = $this->request->get("layout");
        $items = $this->request->get("items");

        $page = \Core\Model\Page::findFirst($id);
        $page->setLayout($layout);
        $page->setWidgets($items);
        $page->save();

        $this->flashSession->success('Page saved!');
        return $response->send();
    }

    /**
     * @Route("/suggest", methods={"GET"}, name="admin-pages-suggest")
     */
    public function suggestAction()
    {
        $this->view->disable();
        $query = $this->request->get('query');
        if (!$query) {
            $this->response->setContent('[]')->send();
            return;
        }


        $results = \Core\Model\Page::find(
            array(
                "conditions" => "title LIKE ?1",
                "bind" => array(1 => '%' . $query . '%')
            )
        );

        $data = array();
        foreach ($results as $result) {
            $data[] = array(
                'id' => $result->getId(),
                'label' => $result->getTitle()
            );
        }

        $this->response->setContent(json_encode($data))->send();
    }

}

