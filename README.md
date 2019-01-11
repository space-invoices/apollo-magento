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


# Supported Versions

* Magento 2.1.*
* Magento 2.2.*
* Magento 2.3.*

# Installation

First thing you need for Apollo to work, is sign up on [Apollo sign up page](https://getapollo.io/signup). After you confirmed email, you can find data you need for you settings at extensions tab.

You can install the module via composer or manually by adding it to the app/code directory. The module is available on [packagist.org](https://packagist.org/packages/apollo/apollo-magento)

Via composer:

``` bash
composer require apollo/apollo-magento;
```

``` bash
php bin/magento setup:upgrade;
```

# Requirements

* php 7.2 or higher
* Magetno 2.0 or higher


Apollo is using [Space invoices API](url=https://spaceinvoices.com/page/home), so for any additional developer information about extension implementation you can check our [Space invoices API PHP documentation](url=https://docs.spaceinvoices.com/?php).