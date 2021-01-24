<?php
namespace Lucky\ControllerTest\Block;


class Index extends \Magento\Framework\View\Element\Template
{
//    public function __construct(Context $context, array $data = [])
//    {
//        parent::__construct($context, $data);
//    }

    public function getFormAction()
    {
//        return $this->getUrl('extension/index/submit', ['_secure' => true]);
        return $this->getUrl('controllertest/index/index');
    }
}