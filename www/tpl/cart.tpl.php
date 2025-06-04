<?php
if (empty($context) || ! is_object($context))
{
    print "Error, template page can't be called as URL";
    exit;
}

global $langs;
$lines = $context->controllerInstance->getLines();
print '<section id="section-cart" class="type-content"><div class="container">';
if (empty($lines)) {
    print '<div class="info text-center">'.$langs->trans('CartEmpty').'</div>';
} else {
    print '<form method="post" action="'.$context->getControllerUrl('cart').'&action=update">';
    print '<table class="table table-striped">';
    print '<thead><tr><th>'.$langs->trans('Product').'</th><th class="text-right">'.$langs->trans('Qty').'</th><th class="text-right">'.$langs->trans('PriceUHT').'</th><th class="text-right">'.$langs->trans('TotalHT').'</th><th></th></tr></thead><tbody>';
    foreach ($lines as $l) {
        $p = $l->product;
        $qty = $l->qty;
        print '<tr>';
        print '<td>'.$p->ref.' - '.dol_escape_htmltag($p->label).'</td>';
        print '<td class="text-right"><input type="number" class="form-control form-control-sm" name="qty['.$p->id.']" value="'.$qty.'" min="0"/></td>';
        print '<td class="text-right">'.price($p->price).'</td>';
        print '<td class="text-right">'.price($p->price * $qty).'</td>';
        print '<td class="text-right"><a class="btn btn-sm btn-danger" href="'.$context->getControllerUrl('cart','remove&pid='.$p->id).'"><i class="fa fa-trash"></i></a></td>';
        print '</tr>';
    }
    print '<tr><td colspan="3" class="text-right"><strong>'.$langs->trans('Total').'</strong></td><td class="text-right"><strong>'.price($context->controllerInstance->getTotal()).'</strong></td><td></td></tr>';
    print '</tbody></table>';
    print '<div class="text-right"><button class="btn btn-primary" type="submit">'.$langs->trans('Save').'</button> ';
    print '<a class="btn btn-success" href="'.$context->getControllerUrl('checkout').'">'.$langs->trans('Validate').'</a></div>';
    print '</form>';
}
print '</div></section>';
