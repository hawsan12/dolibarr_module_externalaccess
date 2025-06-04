<?php
if (empty($context) || ! is_object($context))
{
    print "Error, template page can't be called as URL";
    exit;
}

global $langs;
$lines = $context->lines;
print '<section id="section-checkout" class="type-content"><div class="container">';
if (empty($lines)) {
    print '<div class="info text-center">'.$langs->trans('CartEmpty').'</div>';
} else {
    print '<h3>'.$langs->trans('Checkout').'</h3>';
    print '<form method="post" action="'.$context->getControllerUrl('checkout','confirm').'">';
    print '<table class="table table-striped">';
    print '<thead><tr><th>'.$langs->trans('Product').'</th><th class="text-right">'.$langs->trans('Qty').'</th><th class="text-right">'.$langs->trans('PriceUHT').'</th><th class="text-right">'.$langs->trans('TotalHT').'</th></tr></thead><tbody>';
    $total = 0;
    foreach ($lines as $l) {
        $p = $l->product;
        $qty = $l->qty;
        $lineTotal = $p->price * $qty;
        $total += $lineTotal;
        print '<tr>';
        print '<td>'.$p->ref.' - '.dol_escape_htmltag($p->label).'</td>';
        print '<td class="text-right">'.$qty.'</td>';
        print '<td class="text-right">'.price($p->price).'</td>';
        print '<td class="text-right">'.price($lineTotal).'</td>';
        print '</tr>';
    }
    print '<tr><td colspan="3" class="text-right"><strong>'.$langs->trans('Total').'</strong></td><td class="text-right"><strong>'.price($total).'</strong></td></tr>';
    print '</tbody></table>';
    print '<div class="text-right"><button class="btn btn-success" type="submit">'.$langs->trans('Validate').'</button></div>';
    print '</form>';
}
print '</div></section>';
