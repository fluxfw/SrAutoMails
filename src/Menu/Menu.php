<?php

namespace srag\Plugins\SrAutoMails\Menu;

use ilAdministrationGUI;
use ilDBConstants;
use ILIAS\GlobalScreen\Scope\MainMenu\Factory\AbstractBaseItem;
use ILIAS\GlobalScreen\Scope\MainMenu\Provider\AbstractStaticPluginMainMenuProvider;
use ILIAS\UI\Component\Symbol\Icon\Standard;
use ilObjComponentSettingsGUI;
use ilSrAutoMailsConfigGUI;
use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Menu
 *
 * @package srag\Plugins\SrAutoMails\Menu
 */
class Menu extends AbstractStaticPluginMainMenuProvider
{

    use DICTrait;
    use SrAutoMailsTrait;

    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;


    /**
     * @inheritDoc
     */
    public function getStaticSubItems() : array
    {
        $parent = $this->getStaticTopItems()[0];

        self::dic()
            ->ctrl()
            ->setParameterByClass(ilSrAutoMailsConfigGUI::class, "ref_id", self::dic()
                                                                               ->database()
                                                                               ->queryF('SELECT ref_id FROM object_data INNER JOIN object_reference ON object_data.obj_id=object_reference.obj_id WHERE type=%s',
                                                                                   [ilDBConstants::T_TEXT], ["cmps"])
                                                                               ->fetchAssoc()["ref_id"]);
        self::dic()->ctrl()->setParameterByClass(ilSrAutoMailsConfigGUI::class, "ctype", IL_COMP_SERVICE);
        self::dic()->ctrl()->setParameterByClass(ilSrAutoMailsConfigGUI::class, "cname", "Cron");
        self::dic()->ctrl()->setParameterByClass(ilSrAutoMailsConfigGUI::class, "slot_id", "crnhk");
        self::dic()->ctrl()->setParameterByClass(ilSrAutoMailsConfigGUI::class, "pname", ilSrAutoMailsPlugin::PLUGIN_NAME);

        return [
            $this->symbol($this->mainmenu->link($this->if->identifier(ilSrAutoMailsPlugin::PLUGIN_ID . "_configuration"))
                ->withParent($parent->getProviderIdentification())->withTitle(ilSrAutoMailsPlugin::PLUGIN_NAME)->withAction(self::dic()->ctrl()
                    ->getLinkTargetByClass([
                        ilAdministrationGUI::class,
                        ilObjComponentSettingsGUI::class,
                        ilSrAutoMailsConfigGUI::class
                    ], ilSrAutoMailsConfigGUI::CMD_CONFIGURE))->withAvailableCallable(function () : bool {
                    return self::plugin()->getPluginObject()->isActive();
                })->withVisibilityCallable(function () : bool {
                    return self::dic()->rbac()->review()->isAssigned(self::dic()->user()->getId(), SYSTEM_ROLE_ID);
                }))
        ];
    }


    /**
     * @inheritDoc
     */
    public function getStaticTopItems() : array
    {
        return [
            $this->symbol($this->mainmenu->topParentItem($this->if->identifier(ilSrAutoMailsPlugin::PLUGIN_ID . "_top"))->withTitle(ilSrAutoMailsPlugin::PLUGIN_NAME)
                ->withAvailableCallable(function () : bool {
                    return self::plugin()->getPluginObject()->isActive();
                })->withVisibilityCallable(function () : bool {
                    return self::dic()->rbac()->review()->isAssigned(self::dic()->user()->getId(), SYSTEM_ROLE_ID);
                }))
        ];
    }


    /**
     * @param AbstractBaseItem $entry
     *
     * @return AbstractBaseItem
     */
    protected function symbol(AbstractBaseItem $entry) : AbstractBaseItem
    {
        $entry = $entry->withSymbol(self::dic()->ui()->factory()->symbol()->icon()->standard(Standard::MAIL, ilSrAutoMailsPlugin::PLUGIN_NAME)->withIsOutlined(true));

        return $entry;
    }
}
