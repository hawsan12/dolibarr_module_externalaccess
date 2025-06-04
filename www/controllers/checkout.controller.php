<?php
class CheckoutController extends Controller
{
    public function checkAccess()
    {
        global $conf, $user;
        $this->accessRight = isModEnabled('commande') && isModEnabled('product') && getDolGlobalInt('EACCESS_ACTIVATE_PRODUCTS') && $user->hasRight('externalaccess', 'view_products');
        return parent::checkAccess();
    }

    public function action()
    {
        global $langs, $user, $db;
        $context = Context::getInstance();
        if (!$context->controllerInstance->checkAccess()) { return; }

        $context->title = $langs->trans('Checkout');
        $context->desc  = $langs->trans('CheckoutDesc');
        $context->menu_active[] = 'checkout';

        if ($context->action == 'placeorder' && !empty($_SESSION['cart_lines'])) {
            dol_include_once('commande/class/commande.class.php');
            dol_include_once('product/class/product.class.php');
            $order = new Commande($db);
            $order->socid = $user->socid;
            $order->date_commande = dol_now();
            foreach ($_SESSION['cart_lines'] as $pid => $qty) {
                $prod = new Product($db);
                if ($prod->fetch($pid) > 0) {
                    $order->addline($prod->description, $prod->price, $qty, $prod->tva_tx, 0, 0, $pid, 0, 0, '', 0, 0, null, '', $prod->price_ttc, 0, '', $prod->fk_unit);
                }
            }
            if ($order->create($user) > 0) {
                $_SESSION['cart_lines'] = array();
                $context->setEventMessages($langs->trans('OrderCreated'), 'mesgs');
                header('Location: '.$context->getControllerUrl('orders'));
                exit;
            } else {
                $context->setEventMessages($order->error, 'errors');
            }
        }
    }

    public function display()
    {
        $context = Context::getInstance();
        if (!$context->controllerInstance->checkAccess()) { return $this->display404(); }

        $this->loadTemplate('header');
        $this->loadTemplate('checkout');
        $this->loadTemplate('footer');
    }

    public function getLines()
    {
        require_once __DIR__.'/cart.controller.php';
        $cart = new CartController();
        return $cart->getLines();
    }
}
