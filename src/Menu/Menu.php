<?php

namespace srag\Plugins\SrAutoMails\Menu;

use ilAdministrationGUI;
use ILIAS\GlobalScreen\Scope\MainMenu\Provider\AbstractStaticPluginMainMenuProvider;
use ilObjComponentSettingsGUI;
use ilSrAutoMailsConfigGUI;
use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Menu
 *
 * @package srag\Plugins\SrAutoMails\Menu
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @since   ILIAS 5.4
 */
class Menu extends AbstractStaticPluginMainMenuProvider
{

    use DICTrait;
    use SrAutoMailsTrait;

    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;


    /**
     * @inheritDoc
     */
    public function getStaticTopItems() : array
    {
        return [
            $this->mainmenu->topParentItem($this->if->identifier(ilSrAutoMailsPlugin::PLUGIN_ID . "_top"))->withTitle(ilSrAutoMailsPlugin::PLUGIN_NAME)
                ->withAvailableCallable(function () : bool {
                    return self::plugin()->getPluginObject()->isActive();
                })->withVisibilityCallable(function () : bool {
                    return self::dic()->rbac()->review()->isAssigned(self::dic()->user()->getId(), 2); // Default admin role
                })
        ];
    }


    /**
     * @inheritDoc
     */
    public function getStaticSubItems() : array
    {
        $parent = $this->getStaticTopItems()[0];

        self::dic()->ctrl()->setParameterByClass(ilSrAutoMailsConfigGUI::class, "ref_id", 31);
        self::dic()->ctrl()->setParameterByClass(ilSrAutoMailsConfigGUI::class, "ctype", IL_COMP_SERVICE);
        self::dic()->ctrl()->setParameterByClass(ilSrAutoMailsConfigGUI::class, "cname", "Cron");
        self::dic()->ctrl()->setParameterByClass(ilSrAutoMailsConfigGUI::class, "slot_id", "crnhk");
        self::dic()->ctrl()->setParameterByClass(ilSrAutoMailsConfigGUI::class, "pname", ilSrAutoMailsPlugin::PLUGIN_NAME);

        return [
            $this->mainmenu->link($this->if->identifier(ilSrAutoMailsPlugin::PLUGIN_ID . "_configuration"))
                ->withParent($parent->getProviderIdentification())->withTitle(ilSrAutoMailsPlugin::PLUGIN_NAME)->withAction(self::dic()->ctrl()
                    ->getLinkTargetByClass([
                        ilAdministrationGUI::class,
                        ilObjComponentSettingsGUI::class,
                        ilSrAutoMailsConfigGUI::class
                    ], ilSrAutoMailsConfigGUI::CMD_CONFIGURE))->withAvailableCallable(function () : bool {
                    return self::plugin()->getPluginObject()->isActive();
                })->withVisibilityCallable(function () : bool {
                    return self::dic()->rbac()->review()->isAssigned(self::dic()->user()->getId(), 2); // Default admin role
                })
        ];
    }
}
