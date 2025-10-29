<?php

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Symfony\Component\HttpFoundation\Request;

class FooController extends AbstractContentElementController
{
    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): \Symfony\Component\HttpFoundation\Response|null
    {
        $template->set('foo', 'bar');

        return $template->getResponse();
    }
}
?>
-----
<?php

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Symfony\Component\HttpFoundation\Request;

class FooController extends AbstractContentElementController
{
    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $template->set('foo', 'bar');

        return $template->getResponse();
    }
}
?>
