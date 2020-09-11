<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\DIC\SrAutoMails\DevTools\DevToolsCtrl;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Rule\RulesMailConfigGUI;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class ilSrAutoMailsConfigGUI
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\DIC\SrAutoMails\DevTools\DevToolsCtrl: ilSrAutoMailsConfigGUI
 */
class ilSrAutoMailsConfigGUI extends ilPluginConfigGUI
{

    use DICTrait;
    use SrAutoMailsTrait;

    const CMD_CONFIGURE = "configure";
    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;


    /**
     * ilSrAutoMailsConfigGUI constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function performCommand(/*string*/ $cmd)/*:void*/
    {
        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower(DevToolsCtrl::class):
                self::dic()->ctrl()->forwardCommand(new DevToolsCtrl($this, self::plugin()));
                break;

            case strtolower(RulesMailConfigGUI::class):
                self::dic()->ctrl()->forwardCommand(new RulesMailConfigGUI());
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_CONFIGURE:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     *
     */
    protected function configure()/*: void*/
    {
        self::dic()->ctrl()->redirectByClass(RulesMailConfigGUI::class, RulesMailConfigGUI::CMD_LIST_RULES);
    }


    /**
     *
     */
    protected function setTabs()/*: void*/
    {
        RulesMailConfigGUI::addTabs();

        DevToolsCtrl::addTabs(self::plugin());

        self::dic()->locator()->addItem(ilSrAutoMailsPlugin::PLUGIN_NAME, self::dic()->ctrl()->getLinkTarget($this, self::CMD_CONFIGURE));
    }
}
