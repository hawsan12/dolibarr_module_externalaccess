<?php
if (empty($context) || ! is_object($context))
{
    print "Error, template page can't be called as URL";
    exit;
}

global $langs;
$lines = $context->controllerInstance->getLines();
print '<section id="section-checkout" class="type-content"><div class="container">';
if (empty($lines)) {
    print '<div class="info text-center">'.$langs->trans('CartEmpty').'</div>';
} else {
    print '<h3>'.$langs->trans('Checkout').'</h3>';
    print '<p class="text-center"><a class="btn btn-success" href="'.$context->getControllerUrl('checkout','placeorder').'">'.$langs->trans('Validate').'</a></p>';
}
print '</div></section>';
