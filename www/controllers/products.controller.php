<?php
class ProductsController extends Controller
{
    public function checkAccess()
    {
        global $conf, $user;
        $this->accessRight = isModEnabled('product') && getDolGlobalInt('EACCESS_ACTIVATE_PRODUCTS') && $user->hasRight('externalaccess', 'view_products');
        return parent::checkAccess();
    }

    public function action()
    {
        global $langs;
        $context = Context::getInstance();
        if (!$context->controllerInstance->checkAccess()) { return; }

        $context->title = $langs->trans('ViewProducts');
        $context->desc  = $langs->trans('ViewProductsDesc');
        $context->menu_active[] = 'products';
    }

    public function display()
    {
        $context = Context::getInstance();
        if (!$context->controllerInstance->checkAccess()) { return $this->display404(); }

        $this->loadTemplate('header');
        $this->loadTemplate('product_list');
        $this->loadTemplate('footer');
    }

    public function getProducts()
    {
        global $db;
        dol_include_once('product/class/product.class.php');

        $sql = 'SELECT rowid FROM '.MAIN_DB_PREFIX.'product';
        $sql .= ' WHERE entity IN ('.getEntity('product').') AND tosell = 1';
        $res = $db->query($sql);
        $products = array();
        if ($res) {
            while ($obj = $db->fetch_object($res)) {
                $prod = new Product($db);
                if ($prod->fetch($obj->rowid) > 0) {
                    $products[] = $prod;
                }
            }
        }
        return $products;
    }
}
