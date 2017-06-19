# StockProductStatus
I am currently using Magento 2.1.X CE for one of my projects, and I am facing an issue regarding the configurable product out of stock when filtered by configurable attribute (in make case size attribute) using layered navigation.
## Preconditions

### Project Environment

Magento Version: 2.1.X CE
PHP: 7.0.14

### Magento Settings

Show out of Stock Products: No
Backorder: No
Steps to reproduce

    Create configurable products (for example: Shoes)
    Create Category
    Enable layered navigation for the category
    Assign product to category
    Create linked simple products for sizes (sizes 5.5, 6, 6.5, 7 and so on)
    Set any one of the size (lets say 5.5) to out of stock, others are in-stock
    Set Show out of Stock Products: No
    Set Backorder: No
    Enable Flat Tables
    Run full indexing, flush cache and static content deploy etc.
    Navigate to category view page on Front end end
    Use layered navigation to filter by size attribute choose (5.5 or the size which was set to out of stock)
    The product will be shown even when the filtered by size attribute is out of stock

### Expected Result

Although configurable products are considered out of stock only when all the simple products linked to configurable attribute are Out of Stock, it should consider the condition that when product listing page is filtered by a particular attribute value and the admin setting dictates that Out of stock product should not be shown, then in such cases it should do a stock check against the particular filtered value with the product's configurable attribute instead of checking the stocks of all the simple products linked to it.
Actual Result

Shows the product in the filtered listing page even when the attribute value used to filter the product is Out Of Stock

## Solutions

This module works on child products, automatically enable it when is_in_stock is setted to true and disable when is setted to false.
This module based on event cataloginventory_stock_item_save_after, so before you have to check if have a magento 2 version with the correct file \Magento\CatalogInventory\Model\Stock\Item.php.  