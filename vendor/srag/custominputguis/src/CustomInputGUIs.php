<?php

namespace srag\CustomInputGUIs\SrAutoMails;

use ILIAS\Data\Color;
use srag\CustomInputGUIs\SrAutoMails\LearningProgressPieUI\LearningProgressPieUI;
use srag\CustomInputGUIs\SrAutoMails\PieChart\Component\PieChart as PieChartInterface;
use srag\CustomInputGUIs\SrAutoMails\PieChart\Component\PieChartItem as PieChartItemInterface;
use srag\CustomInputGUIs\SrAutoMails\PieChart\Implementation\PieChart;
use srag\CustomInputGUIs\SrAutoMails\PieChart\Implementation\PieChartItem;
use srag\CustomInputGUIs\SrAutoMails\ViewControlModeUI\ViewControlModeUI;
use srag\DIC\SrAutoMails\DICTrait;

//use ILIAS\UI\Component\Chart\PieChart\PieChart as PieChartInterfaceCore;
//use ILIAS\UI\Component\Chart\PieChart\PieChartItem as PieChartItemInterfaceCore;
//use ILIAS\UI\Implementation\Component\Chart\PieChart\PieChart as PieChartCore;
//use ILIAS\UI\Implementation\Component\Chart\PieChart\PieChartItem as PieChartItemCore;

/**
 * Class CustomInputGUIs
 *
 * @package srag\CustomInputGUIs\SrAutoMails
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class CustomInputGUIs
{

    use DICTrait;

    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * CustomInputGUIs constructor
     */
    private function __construct()
    {

    }


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @return LearningProgressPieUI
     */
    public function learningProgressPie() : LearningProgressPieUI
    {
        return new LearningProgressPieUI();
    }


    /**
     * @param PieChartItemInterfaceCore[]|PieChartItemInterface[] $pieChartItems
     *
     * @return PieChartInterfaceCore|PieChartInterface
     *
     * @since ILIAS 6.0
     */
    public function pieChart(array $pieChartItems)
    {
        /*if (self::version()->is6()) {
            return new PieChartCore($pieChartItems);
        } else {*/
        return new PieChart($pieChartItems);
        //}
    }


    /**
     * @param string     $name
     * @param float      $value
     * @param Color      $color
     * @param Color|null $textColor
     *
     * @return PieChartItemInterfaceCore|PieChartItemInterface
     *
     * @since ILIAS 6.0
     */
    public function pieChartItem(string $name, float $value, Color $color, /*?*/ Color $textColor = null)
    {
        /*if (self::version()->is6()) {
            return new PieChartItemCore($name, $value, $color, $textColor);
        } else {*/
        return new PieChartItem($name, $value, $color, $textColor);
        //}
    }


    /**
     * @return ViewControlModeUI
     */
    public function viewControlMode() : ViewControlModeUI
    {
        return new ViewControlModeUI();
    }
}
