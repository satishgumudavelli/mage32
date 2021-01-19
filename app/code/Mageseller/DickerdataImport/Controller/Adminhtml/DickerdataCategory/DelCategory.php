<?php

namespace Mageseller\DickerdataImport\Controller\Adminhtml\DickerdataCategory;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Mageseller\DickerdataImport\Helper\EavAtrributeUpdateHelper;
use Mageseller\DickerdataImport\Helper\EavAttributeDataWithoutLoad;
use Mageseller\DickerdataImport\Helper\Dickerdata;

class DelCategory extends Action
{
    /** @var  Page */
    protected $resultPageFactory;
    protected $helper;
    protected $_registry;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var EavAttributeDataWithoutLoad
     */
    private $attributeDataWithoutLoad;
    /**
     * @var EavAtrributeUpdateHelper
     */
    private $eavAtrributeUpdateHelper;

    /**      *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Dickerdata $helper
     * @param EavAtrributeUpdateHelper $eavAtrributeUpdateHelper
     * @param EavAttributeDataWithoutLoad $attributeDataWithoutLoad
     * @param array $data
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Dickerdata $helper,
        EavAtrributeUpdateHelper $eavAtrributeUpdateHelper,
        EavAttributeDataWithoutLoad $attributeDataWithoutLoad,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->eavAtrributeUpdateHelper = $eavAtrributeUpdateHelper;
        $this->attributeDataWithoutLoad = $attributeDataWithoutLoad;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return PageFactory
     */
    public function execute()
    {
        if ($postData = $this->getRequest()->getParams()) {
            $shopId = $postData['shopId'] ?? "";
            $supplierId = $postData['supplierId'] ?? "";
            if ($shopId) {
                $dickerdataCategoryIds = $this->attributeDataWithoutLoad->getCategoryAttributeRawValue($shopId, ['dickerdata_category_ids' ]);
                if ($dickerdataCategoryIds) {
                    $dickerdataCategoryIds = explode(",", $dickerdataCategoryIds);
                    $dickerdataCategoryIds = array_diff($dickerdataCategoryIds, [$supplierId]);
                }
                if ($dickerdataCategoryIds) {
                    $this->eavAtrributeUpdateHelper->updateCategoryAttributes([$shopId], ['dickerdata_category_ids' => implode(",", $dickerdataCategoryIds)]);
                } else {
                    $this->eavAtrributeUpdateHelper->updateCategoryAttributes([$shopId], ['dickerdata_category_ids' => null]);
                }
            }
        }
        $result = $this->resultFactory->create($this->resultFactory::TYPE_RAW);
        $result->setContents($this->helper->getCategories(1));
        return $result;
    }
}