<?php

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\ModuleModel;
use Symfony\Component\HttpFoundation\Request;

class FooController extends AbstractFrontendModuleController
{
    protected function getResponse(FragmentTemplate $template, ModuleModel $model, Request $request): \Symfony\Component\HttpFoundation\Response|null
    {
        $template->set('foo', 'bar');

        return $template->getResponse();
    }
}
?>
-----
<?php

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\ModuleModel;
use Symfony\Component\HttpFoundation\Request;

class FooController extends AbstractFrontendModuleController
{
    protected function getResponse(FragmentTemplate $template, ModuleModel $model, Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $template->set('foo', 'bar');

        return $template->getResponse();
    }
}
?>
