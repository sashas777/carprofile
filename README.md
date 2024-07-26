## Overview
Establishes a connection between Adobe Commerce and a Cars API service, allowing customers to add and manage car details within their customer profiles. 

## Installation

Run the following command at Magento 2 root folder:

```
composer require thesgroup/module-razoyo-carprofile
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
```

## Uninstallation
```
composer remove thesgroup/module-razoyo-carprofile
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
```

## Configuration
The module can be configured at the Stores -> Configuration -> Customers -> Car Profile

## Limitations
- An API response should be within 30 seconds.
- One customer may have only one car.

## Possible Improvements
- GQL Interfaces for a headless theme.
- Admin management for customer's extension attribute.
- Ability to remove a car, currently possible to remove it.
- Ability to save more than one car. 
- Support for switch between sandbox/production environment for API with a dropdown option
- Implement cache for API requests responses 
- Add Unit tests

## Showcase

![](https://github.com/sashas777/assets/raw/master/carprofile_1.png)
![](https://github.com/sashas777/assets/raw/master/carprofile_2.png)
![](https://github.com/sashas777/assets/raw/master/carprofile_3.png)
![](https://github.com/sashas777/assets/raw/master/carprofile_4.png)
![](https://github.com/sashas777/assets/raw/master/carprofile_5.png)



 
