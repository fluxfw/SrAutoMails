## Installation

### Install SrAutoMails-Plugin
Start at your ILIAS root directory
```bash
mkdir -p Customizing/global/plugins/Services/Cron/CronHook
cd Customizing/global/plugins/Services/Cron/CronHook
git clone https://github.com/studer-raimann/SrAutoMails.git SrAutoMails
```
Update and activate the plugin in the ILIAS Plugin Administration

### Some screenshots
Rule:
![Rule](./doc/screenshots/rule.png)

### Dependencies
* ILIAS 5.3
* PHP >=7.0
* [composer](https://getcomposer.org)
* [Notifications4Plugins](https://github.com/studer-raimann/Notifications4Plugins)
* [srag/activerecordconfig](https://packagist.org/packages/srag/activerecordconfig)
* [srag/custominputguis](https://packagist.org/packages/srag/custominputguis)
* [srag/dic](https://packagist.org/packages/srag/dic)
* [srag/librariesnamespacechanger](https://packagist.org/packages/srag/librariesnamespacechanger)
* [srag/removeplugindataconfirm](https://packagist.org/packages/srag/removeplugindataconfirm)

Please use it for further development!

### Adjustment suggestions
* Adjustment suggestions by pull requests on https://git.studer-raimann.ch/ILIAS/Plugins/SrAutoMails/tree/develop
* Adjustment suggestions which are not yet worked out in detail by Jira tasks under https://jira.studer-raimann.ch/projects/PLSRAUMA
* Bug reports under https://jira.studer-raimann.ch/projects/PLSRAUMA
* For external users please send an email to support-custom1@studer-raimann.ch

### Development
If you want development in this plugin you should install this plugin like follow:

Start at your ILIAS root directory
```bash
mkdir -p Customizing/global/plugins/Services/Cron/CronHook
cd Customizing/global/plugins/Services/Cron/CronHook
git clone -b develop git@git.studer-raimann.ch:ILIAS/Plugins/SrAutoMails.git SrAutoMails
```
