Create Controller in Magento 2

Step 1: Create routes.xml file.

File: /etc/frontend/routes.xml

```xml
<?xml version="1.0" ?>
  <config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:App/etc/routes.xsd">
      <router id="standard">
          <route frontName="controllertest" id="controllertest">
              <module name="Lucky_ControllerTest"/>
          </route>
      </router>
  </config>
```

Step 2: Create controller file

File: /Controller/Index/Index.php

```php
<?php

namespace Lucky\ControllerTest\Controller\Index;


class Index extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        $post = (array) $this->getRequest()->getPost();
        if (!empty($post)) {
            // Retrieve your form data
            $firstname   = $post['firstname'];
            $lastname    = $post['lastname'];
            $phone       = $post['phone'];
            $bookingTime = $post['bookingTime'];
            dump($post);
            exit;
            // Doing-something with...

            // Display the succes form validation message
            $this->messageManager->addSuccessMessage('Booking done !');

            // Redirect to your form page (or anywhere you want...)
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl('/companymodule/index/booking');

            return $resultRedirect;
        }
        return $this->_pageFactory->create();
    }
}

```

Step 3: Create Layout file

File: /view/frontend/layout/helloworld_index_index.xml

```xml
<?xml version="1.0"?>
<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" layout="1column" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
    <referenceContainer name="content">
        <block class="Lucky\ControllerTest\Block\Index" name="controllertest_index_index" template="Lucky_ControllerTest::index.phtml" />
    </referenceContainer>
</page>
```

Step 4: Create Block file

File: /Block/Index.php

```php
<?php
namespace Lucky\ControllerTest\Block;


class Index extends \Magento\Framework\View\Element\Template
{
    public function getFormAction()
    {
        return $this->getUrl('controllertest/index/index');
    }
}
```

Step 5: Create template file

File: /view/frontend/templates/index.phtml

```html
<h1>Booking page</h1>

<form action="<?php echo $block->getFormAction() ?>" method="post">
    <input name="firstname" type="text">
    <input name="lastname" type="text">
    <input name="phone" type="text">
    <input name="bookingTime" type="date">
    <input type="submit" value="Send booking informations">
</form>
```

Step 6: Enable Module

```
php bin/magento module:enable Lucky_ControllerTest
```

Step 7: Upgrade Module
```
php bin/magento setup:upgrade
```

Step 8: Flush Magento cache
```
php bin/magento cache:flush
```

Step 9: Run a test

```html
http://<yourhost.com>/controllertest/index/index
```
or
```html
http://<yourhost.com>/controllertest/
```
