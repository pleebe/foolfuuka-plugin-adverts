<?php

use Foolz\FoolFrame\Model\Autoloader;
use Foolz\FoolFrame\Model\Context;
use Foolz\Plugin\Event;

class HHVM_Ads
{
    public function run()
    {
        Event::forge('Foolz\Plugin\Plugin::execute#foolz/foolfuuka-plugin-adverts')
            ->setCall(function ($result) {
                /* @var Context $context */
                $context = $result->getParam('context');
                /** @var Autoloader $autoloader */
                $autoloader = $context->getService('autoloader');
                $autoloader->addClassMap([
                    'Foolz\FoolFrame\Controller\Admin\Plugins\Adverts' => __DIR__ . '/classes/controller/admin.php',
                    'Foolz\FoolFuuka\Plugins\Adverts\Model\Ads' => __DIR__ . '/classes/model/ads.php'
                ]);

                $context->getContainer()
                    ->register('foolfuuka-plugin.adverts', 'Foolz\FoolFuuka\Plugins\Adverts\Model\Ads')
                    ->addArgument($context);

                Event::forge(['foolfuuka.themes.default_after_op_open', 'foolfuuka.themes.default_after_headless_open'])
                    ->setCall('Foolz\FoolFuuka\Plugins\Adverts\Model\Ads::display')
                    ->setPriority(5);

                Event::forge(['foolfuuka.themes.default_after_body_template', 'foolfuuka.themes.fuuka_after_body_template'])
                    ->setCall('Foolz\FoolFuuka\Plugins\Adverts\Model\Ads::displayunder')
                    ->setPriority(5);

                Event::forge('Foolz\FoolFrame\Model\Context::handleWeb#obj.afterAuth')
                    ->setCall(function ($result) use ($context) {
                        // don't add the admin panels if the user is not an admin
                        if ($context->getService('auth')->hasAccess('maccess.admin')) {
                            $context->getRouteCollection()->add(
                                'foolfuuka.plugin.adverts.admin', new \Symfony\Component\Routing\Route(
                                    '/admin/plugins/adverts/{_suffix}',
                                    [
                                        '_suffix' => 'manage',
                                        '_controller' => 'Foolz\FoolFrame\Controller\Admin\Plugins\Adverts::manage'
                                    ],
                                    [
                                        '_suffix' => '.*'
                                    ]
                                )
                            );

                            Event::forge('Foolz\FoolFrame\Controller\Admin::before#var.sidebar')
                                ->setCall(function ($result) {
                                    $sidebar = $result->getParam('sidebar');
                                    $sidebar[]['plugins'] = [
                                        'content' => ['adverts/manage' => ['level' => 'admin', 'name' => 'Advertisement Preferences', 'icon' => 'icon-lock']]
                                    ];
                                    $result->setParam('sidebar', $sidebar);
                                });
                        }
                    });
            });
    }
}

(new HHVM_Ads())->run();
