<?php
if (empty($context) || ! is_object($context))
{
    print "Error, template page can't be called as URL";
    exit;
}

global $langs;
$products = $context->controllerInstance->getProducts();
print '<section id="section-products" class="type-content"><div class="container">';
if (empty($products)) {
    print '<div class="info text-center">'.$langs->trans('SorryThereIsNothingHere').'</div>';
} else {
    print '<div class="row">';
    foreach ($products as $p) {
        print '<div class="col-md-4 mb-4">';
        print '<div class="card h-100 text-center">';
        print '<img class="card-img-top p-3" src="'.getProductImgUrl($p->id, 'thumb').'" alt="">';
        print '<div class="card-body">';
        print '<h5 class="card-title">'.dol_escape_htmltag($p->ref).'</h5>';
        print '<p class="card-text">'.dol_escape_htmltag($p->label).'</p>';
        print '<p class="card-text"><strong>'.price($p->price).'</strong></p>';
        print '<a class="btn btn-primary btn-strong" href="'.$context->getControllerUrl('cart', 'add&pid='.$p->id).'"><i class="fa fa-cart-plus"></i> '.$langs->trans('AddToCart').'</a>';
        print '</div></div></div>';
    }
    print '</div>';
}
print '</div></section>';
