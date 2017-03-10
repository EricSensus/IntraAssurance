<?php
echo '<div class="setup-left">'
. '<h4 class="mb5 text-light">Generic Entities Configuration</h4>'
. '<p>Entities are the actual items being insured. They can be a person, car or property etc. '
        . 'This allows for easy management of all the various insured entities under one customer</p>'
. '<p>This section allows you to set the form fields for each generic entity within the system</p>';

echo '</div>';

echo '<div class="setup-right">';
echo $entitiestable;
//echo $deletemodal;
echo '</div>';

