# demo-product_details module

Before installing this module, run below composer command to download the phpqrcode library 
composer require pendalff/phpqrcode

place the module product_details under/modules/custom directory
enable the module using the command drush en product_details
go to block layout and place the block "Scan Here To Purchase This Product" to "sidebar second" region and configure the block to be visible only on "Product Details" content type
create contents of "Product Details" type on view content page you can see the QR code for buying product link

demo site hosted here https://dev-demo-drupal-site-demo.pantheonsite.io/ with some dummy contents added.
