<?php

namespace Mageseller\LeadersystemsImport\Plugin;

class CategoryDropdownPlugin
{
    public function afterGetData(\Magento\Catalog\Model\Category\DataProvider $subject, $result)
    {
        $category = $subject->getCurrentCategory();
        if (isset($result[$category->getId()]['leadersystems_category_ids'])) {
            $result[$category->getId()]['leadersystems_category_ids'] = explode(",", $result[$category->getId()]['leadersystems_category_ids']);
        }
        return $result;
    }
}