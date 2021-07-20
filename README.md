# SrAutoMails ILIAS Plugin

Auto send mails per rules

This project is licensed under the GPL-3.0-only license

## Requirements

* ILIAS 6.0 - 7.999
* PHP >=7.2

## Installation

Start at your ILIAS root directory

```bash
mkdir -p Customizing/global/plugins/Services/Cron/CronHook
cd Customizing/global/plugins/Services/Cron/CronHook
git clone https://github.com/fluxapps/SrAutoMails.git SrAutoMails
```

Update, activate and config the plugin in the ILIAS Plugin Administration

### ILIAS 7

For make this plugin work with ilCtrl in ILIAS 7, you may need to patch the core, before you update the plugin (At your own risk)

Start at the plugin directory

```bash
chmod +x bin/ilias7_apply_ilctrl_patch.sh
bin/ilias7_apply_ilctrl_patch.sh
```

## Description

Rule:
![Rule](./doc/images/rule.png)
