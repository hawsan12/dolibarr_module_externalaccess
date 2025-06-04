<?php
class CartController extends Controller
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

        $context->title = $langs->trans('Cart');
        $context->desc  = $langs->trans('CartDesc');
        $context->menu_active[] = 'cart';

        if ($context->action == 'add') {
            $pid = GETPOST('pid', 'int');
            $qty = GETPOST('qty', 'int');
            if ($pid > 0) {
                if (empty($_SESSION['cart_lines'][$pid])) $_SESSION['cart_lines'][$pid] = 0;
                if ($qty <= 0) $qty = 1;
                $_SESSION['cart_lines'][$pid] += $qty;
                $context->setEventMessages($langs->trans('AddedToCart'), 'mesgs');
            }
        } elseif ($context->action == 'remove') {
            $pid = GETPOST('pid', 'int');
            unset($_SESSION['cart_lines'][$pid]);
        } elseif ($context->action == 'update' && !empty($_POST['qty'])) {
            foreach ($_POST['qty'] as $pid => $qty) {
                $qty = intval($qty);
                if ($qty <= 0) unset($_SESSION['cart_lines'][$pid]);
                else $_SESSION['cart_lines'][$pid] = $qty;
            }
            $context->setEventMessages($langs->trans('Saved'), 'mesgs');
        }
    }

    public function display()
    {
        $context = Context::getInstance();
        if (!$context->controllerInstance->checkAccess()) { return $this->display404(); }

        $this->loadTemplate('header');
        $this->loadTemplate('cart');
        $this->loadTemplate('footer');
    }

    public function getLines()
    {
        global $db;
        dol_include_once('product/class/product.class.php');
        $lines = array();
        if (!empty($_SESSION['cart_lines'])) {
            foreach ($_SESSION['cart_lines'] as $pid => $qty) {
                $prod = new Product($db);
                if ($prod->fetch($pid) > 0) {
                    $line = new stdClass();
                    $line->product = $prod;
                    $line->qty = $qty;
                    $lines[$pid] = $line;
                }
            }
        }
        return $lines;
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->getLines() as $line) {
            $total += $line->product->price * $line->qty;
        }
        return $total;
    }
}
