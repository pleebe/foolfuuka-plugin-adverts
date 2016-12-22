<?php

namespace Foolz\FoolFrame\Controller\Admin\Plugins;

use Foolz\FoolFrame\Model\Validation\ActiveConstraint\Trim;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Adverts extends \Foolz\FoolFrame\Controller\Admin
{
    protected $adverts;

    public function before()
    {
        parent::before();

        $this->adverts = $this->getContext()->getService('foolfuuka-plugin.adverts');

        $this->param_manager->setParam('controller_title', 'Advertisement Preferences');
    }

    public function security()
    {
        return $this->getAuth()->hasAccess('maccess.mod');
    }

    function structure()
    {
        return [
            'open' => [
                'type' => 'open',
            ],
            'foolfuuka.plugin.adverts.code' => [
                'preferences' => true,
                'type' => 'textarea',
                'label' => _i('Work safe ad code at the top'),
                'help' => _i('Will be displayed on non lewd boards.'),
                'class' => 'span8'
            ],
            'foolfuuka.plugin.adverts.codensfw' => [
                'preferences' => true,
                'type' => 'textarea',
                'label' => _i('Not work safe ad code at the top'),
                'help' => _i('Will be displayed on lewd boards.'),
                'class' => 'span8'
            ],
            'foolfuuka.plugin.adverts.codebottom' => [
                'preferences' => true,
                'type' => 'textarea',
                'label' => _i('Work safe ad code at the bottom'),
                'help' => _i('Will be displayed on non lewd boards and front page.'),
                'class' => 'span8'
            ],
            'foolfuuka.plugin.adverts.codensfwbottom' => [
                'preferences' => true,
                'type' => 'textarea',
                'label' => _i('Not work safe ad code at the bottom'),
                'help' => _i('Will be displayed on lewd boards.'),
                'class' => 'span8'
            ],
            'separator-2' => [
                'type' => 'separator-short'
            ],
            'submit' => [
                'type' => 'submit',
                'class' => 'btn-primary',
                'value' => _i('Submit')
            ],
            'close' => [
                'type' => 'close'
            ],
        ];
    }

    function action_manage()
    {
        $this->param_manager->setParam('method_title', 'Manage');

        $data['form'] = $this->structure();

        $this->preferences->submit_auto($this->getRequest(), $data['form'], $this->getPost());
        $this->builder->createPartial('body', 'form_creator')->getParamManager()->setParams($data);

        return new Response($this->builder->build());
    }
}
