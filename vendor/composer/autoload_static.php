<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1760e46ce99334f16b04a6042182988e
{
    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'srag\\RemovePluginDataConfirm\\' => 29,
            'srag\\Plugins\\SrAutoMails\\' => 25,
            'srag\\DIC\\' => 9,
            'srag\\CustomInputGUIs\\' => 21,
            'srag\\ActiveRecordConfig\\' => 24,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'srag\\RemovePluginDataConfirm\\' => 
        array (
            0 => __DIR__ . '/..' . '/srag/removeplugindataconfirm/src',
        ),
        'srag\\Plugins\\SrAutoMails\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'srag\\DIC\\' => 
        array (
            0 => __DIR__ . '/..' . '/srag/dic/src',
        ),
        'srag\\CustomInputGUIs\\' => 
        array (
            0 => __DIR__ . '/..' . '/srag/custominputguis/src',
        ),
        'srag\\ActiveRecordConfig\\' => 
        array (
            0 => __DIR__ . '/..' . '/srag/activerecordconfig/src',
        ),
    );

    public static $classMap = array (
        'SrAutoMailsRemoveDataConfirm' => __DIR__ . '/../..' . '/classes/uninstall/class.SrAutoMailsRemoveDataConfirm.php',
        'ilSrAutoMailsConfigGUI' => __DIR__ . '/../..' . '/classes/class.ilSrAutoMailsConfigGUI.php',
        'ilSrAutoMailsPlugin' => __DIR__ . '/../..' . '/classes/class.ilSrAutoMailsPlugin.php',
        'srag\\ActiveRecordConfig\\ActiveRecordConfig' => __DIR__ . '/..' . '/srag/activerecordconfig/src/ActiveRecordConfig.php',
        'srag\\ActiveRecordConfig\\ActiveRecordConfigFormGUI' => __DIR__ . '/..' . '/srag/activerecordconfig/src/ActiveRecordConfigFormGUI.php',
        'srag\\ActiveRecordConfig\\ActiveRecordConfigGUI' => __DIR__ . '/..' . '/srag/activerecordconfig/src/ActiveRecordConfigGUI.php',
        'srag\\ActiveRecordConfig\\ActiveRecordConfigTableGUI' => __DIR__ . '/..' . '/srag/activerecordconfig/src/ActiveRecordConfigTableGUI.php',
        'srag\\ActiveRecordConfig\\Exception\\ActiveRecordConfigException' => __DIR__ . '/..' . '/srag/activerecordconfig/src/Exception/ActiveRecordConfigException.php',
        'srag\\CustomInputGUIs\\DateDurationInputGUI\\DateDurationInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/DateDurationInputGUI/DateDurationInputGUI.php',
        'srag\\CustomInputGUIs\\GlyphGUI\\GlyphGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/GlyphGUI/GlyphGUI.php',
        'srag\\CustomInputGUIs\\MultiLineInputGUI\\MultiLineInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/MultiLineInputGUI/MultiLineInputGUI.php',
        'srag\\CustomInputGUIs\\MultiSelectSearchInputGUI\\MultiSelectSearchInput2GUI' => __DIR__ . '/..' . '/srag/custominputguis/src/MultiSelectSearchInputGUI/MultiSelectSearchInput2GUI.php',
        'srag\\CustomInputGUIs\\MultiSelectSearchInputGUI\\MultiSelectSearchInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/MultiSelectSearchInputGUI/MultiSelectSearchInputGUI.php',
        'srag\\CustomInputGUIs\\NumberInputGUI\\NumberInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/NumberInputGUI/NumberInputGUI.php',
        'srag\\CustomInputGUIs\\ScreenshotsInputGUI\\ScreenshotsInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/ScreenshotsInputGUI/ScreenshotsInputGUI.php',
        'srag\\CustomInputGUIs\\StaticHTMLPresentationInputGUI\\StaticHTMLPresentationInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/StaticHTMLPresentationInputGUI/StaticHTMLPresentationInputGUI.php',
        'srag\\CustomInputGUIs\\Template\\Template' => __DIR__ . '/..' . '/srag/custominputguis/src/Template/Template.php',
        'srag\\CustomInputGUIs\\TextAreaInputGUI\\TextAreaInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/TextAreaInputGUI/TextAreaInputGUI.php',
        'srag\\CustomInputGUIs\\TextInputGUI\\TextInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/TextInputGUI/TextInputGUI.php',
        'srag\\CustomInputGUIs\\Waiter\\Waiter' => __DIR__ . '/..' . '/srag/custominputguis/src/Waiter/Waiter.php',
        'srag\\DIC\\DICStatic' => __DIR__ . '/..' . '/srag/dic/src/DICStatic.php',
        'srag\\DIC\\DICStaticInterface' => __DIR__ . '/..' . '/srag/dic/src/DICStaticInterface.php',
        'srag\\DIC\\DICTrait' => __DIR__ . '/..' . '/srag/dic/src/DICTrait.php',
        'srag\\DIC\\DIC\\AbstractDIC' => __DIR__ . '/..' . '/srag/dic/src/DIC/AbstractDIC.php',
        'srag\\DIC\\DIC\\DICInterface' => __DIR__ . '/..' . '/srag/dic/src/DIC/DICInterface.php',
        'srag\\DIC\\DIC\\LegacyDIC' => __DIR__ . '/..' . '/srag/dic/src/DIC/LegacyDIC.php',
        'srag\\DIC\\DIC\\NewDIC' => __DIR__ . '/..' . '/srag/dic/src/DIC/NewDIC.php',
        'srag\\DIC\\Exception\\DICException' => __DIR__ . '/..' . '/srag/dic/src/Exception/DICException.php',
        'srag\\DIC\\Plugin\\Plugin' => __DIR__ . '/..' . '/srag/dic/src/Plugin/Plugin.php',
        'srag\\DIC\\Plugin\\PluginInterface' => __DIR__ . '/..' . '/srag/dic/src/Plugin/PluginInterface.php',
        'srag\\DIC\\Plugin\\Pluginable' => __DIR__ . '/..' . '/srag/dic/src/Plugin/Pluginable.php',
        'srag\\DIC\\Version\\Version' => __DIR__ . '/..' . '/srag/dic/src/Version/Version.php',
        'srag\\DIC\\Version\\VersionInterface' => __DIR__ . '/..' . '/srag/dic/src/Version/VersionInterface.php',
        'srag\\Plugins\\SrAutoMails\\Access\\Courses' => __DIR__ . '/../..' . '/src/Access/Courses.php',
        'srag\\Plugins\\SrAutoMails\\Access\\Ilias' => __DIR__ . '/../..' . '/src/Access/Ilias.php',
        'srag\\Plugins\\SrAutoMails\\Access\\Metadata' => __DIR__ . '/../..' . '/src/Access/Metadata.php',
        'srag\\Plugins\\SrAutoMails\\Access\\OrgUnits' => __DIR__ . '/../..' . '/src/Access/OrgUnits.php',
        'srag\\Plugins\\SrAutoMails\\Access\\Users' => __DIR__ . '/../..' . '/src/Access/Users.php',
        'srag\\Plugins\\SrAutoMails\\Config\\Config' => __DIR__ . '/../..' . '/src/Config/Config.php',
        'srag\\Plugins\\SrAutoMails\\Job\\Job' => __DIR__ . '/../..' . '/src/Job/Job.php',
        'srag\\Plugins\\SrAutoMails\\ObjectType\\ObjectType' => __DIR__ . '/../..' . '/src/ObjectType/ObjectType.php',
        'srag\\Plugins\\SrAutoMails\\ObjectType\\ObjectTypes' => __DIR__ . '/../..' . '/src/ObjectType/ObjectTypes.php',
        'srag\\Plugins\\SrAutoMails\\ObjectType\\Object\\CourseObjectType' => __DIR__ . '/../..' . '/src/ObjectType/Object/CourseObjectType.php',
        'srag\\Plugins\\SrAutoMails\\ObjectType\\Object\\ObjObjectType' => __DIR__ . '/../..' . '/src/ObjectType/Object/ObjObjectType.php',
        'srag\\Plugins\\SrAutoMails\\Rule\\Rule' => __DIR__ . '/../..' . '/src/Rule/Rule.php',
        'srag\\Plugins\\SrAutoMails\\Rule\\RuleFormGUI' => __DIR__ . '/../..' . '/src/Rule/RuleFormGUI.php',
        'srag\\Plugins\\SrAutoMails\\Rule\\Rules' => __DIR__ . '/../..' . '/src/Rule/Rules.php',
        'srag\\Plugins\\SrAutoMails\\Rule\\RulesTableGUI' => __DIR__ . '/../..' . '/src/Rule/RulesTableGUI.php',
        'srag\\Plugins\\SrAutoMails\\Sent\\Sent' => __DIR__ . '/../..' . '/src/Sent/Sent.php',
        'srag\\Plugins\\SrAutoMails\\Utils\\SrAutoMailsTrait' => __DIR__ . '/../..' . '/src/Utils/SrAutoMailsTrait.php',
        'srag\\RemovePluginDataConfirm\\AbstractPluginUninstallTrait' => __DIR__ . '/..' . '/srag/removeplugindataconfirm/src/AbstractPluginUninstallTrait.php',
        'srag\\RemovePluginDataConfirm\\AbstractRemovePluginDataConfirm' => __DIR__ . '/..' . '/srag/removeplugindataconfirm/src/AbstractRemovePluginDataConfirm.php',
        'srag\\RemovePluginDataConfirm\\PluginUninstallTrait' => __DIR__ . '/..' . '/srag/removeplugindataconfirm/src/PluginUninstallTrait.php',
        'srag\\RemovePluginDataConfirm\\RemovePluginDataConfirmException' => __DIR__ . '/..' . '/srag/removeplugindataconfirm/src/RemovePluginDataConfirmException.php',
        'srag\\RemovePluginDataConfirm\\RepositoryObjectPluginUninstallTrait' => __DIR__ . '/..' . '/srag/removeplugindataconfirm/src/RepositoryObjectPluginUninstallTrait.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1760e46ce99334f16b04a6042182988e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1760e46ce99334f16b04a6042182988e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1760e46ce99334f16b04a6042182988e::$classMap;

        }, null, ClassLoader::class);
    }
}
