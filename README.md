# Apollo for Magento

#### About Apollo
Apollo is an intuitive all-in-one online invoicing software allowing you to create, edit and send professional invoices with ease. With Apollo, it’s easier-than-ever to create professional invoices.

**Apollo features**
- Overview your business
- Track payments, partial payments and overdue invoices
- Create documents of varying types (invoices, estimates, etc)
- Easily duplicate or convert documents from one type to another
- Manage invoiceable items and services
- Manage your clients with status overview and robust contact database
- Manage user accounts with read and/or write permission to your data
- Manage multiple organizations with one user account

You can get more information about Apollo at [https://getapollo.io](https://getapollo.io).

#### About Apollo Magento extension

This extension offers some of the features from Apollo, such as creating estimates and invoices and creating PDFs for orders.
You can easily create invoices in Magento, and track all invoicing data on [Apollo webpage](https://getapollo.io).

#### Main features
- Automatic (or manual) creating invoices for individual order
- Automatic estimate creating
- Create, view and download PDF documents for each invoice or estimate
- Sending email with PDF attachment of invoice or estimates (can work automatically, or you can send if after any order status update)
- Tracking your invoices/estimates at [https://getapollo.io](https://getapollo.io)

#### PDF customization

You can customize your PDF document at Apollo webpage, where you can set PDF logo, choose primary color and set all of the PDF texts.
You can do that by simply going to [Apollo](https://getapollo.io), find "Account" icon on the bottom of sidebar and click "Customizations" under your organization name.

# Installation

First thing you need for Apollo to work, is sign up on [Apollo sign up page](https://getapollo.io/signup). After you confirmed email, you can find data you need for you settings at extensions tab.

You can install the module via composer or manually by adding it to the app/code directory. The module is available on [packagist.org](https://packagist.org/packages/space-invoices/apollo-magento).

##### Via composer:

1. Navigate to your Magento project directory and from command line run:

``` bash
composer require space-invoices/apollo-magento
```
2. After that enable Apollo module with command:

``` bash
php bin/magento module:enable Studio404_Apollo
```

3. Install extension:

``` bash
php bin/magento setup:upgrade
```
4. And deploy:

``` bash
php bin/magento setup:static-content:deploy
```

##### Manually:

1. Navigate to your Magento project directory `/app/code`

2. Create folder named `Studio404` and inside another folder named `Apollo`

3. Download project as ZIP file and extract it.

4. Copy extracted folder content to `/app/code/Studio404/Apollo` folder.

5. From a command line run:

``` bash
php bin/magento module:enable Studio404_Apollo
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```
# How to use

**For extension to work you need to provide Apollo token and Apollo organization ID. You can find those on our [Apollo page](https://getapollo.io), under extensions tab.**

### Automatic creating/sending

You can find extension settings under Stores > Configuration > Apollo Settings > General.
There you can your Apollo credentials and setup automatic invoice sending. You can control when invoice is generated and sent to costumer by setting "Order status", which sends invoice only for orders that match selected status.

Estimates can be sent automatically, but only for "Direct bank transfer" payments.

It is also possible to set "Mail message", which sets the body of email in which the document will be sent.

### Manual creating/sending

Invoices and estimates can be created manually for each order, that doesen't have Apollo invoice/estimate yet.

You can find "Apollo" section under "Payment & Shipping Method" section on the order view page. There you can create inovice/estimate.
After invoice or estimate is created, you can directly download PDF, send PDF to customer or view document on Apollo page, where you will find extra features.

# Requirements

* php 7.0 or higher
* Magetno 2.0 or higher


Apollo is using [Space invoices API](https://spaceinvoices.com/page/home), so for any additional developer information about extension implementation you can check our [Space invoices API PHP documentation](https://docs.spaceinvoices.com/?php).