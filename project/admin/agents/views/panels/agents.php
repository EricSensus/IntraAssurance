<?php
echo '<div class="setup-left">'
. '<h4 class="mb5 text-light">Users / Agents Configuration</h4>'
. '<p>This section allows you to set the form fields for the users / agents interacting with the system</p>';

echo '</div>';

echo '<div class="setup-right">';
echo $agents_table;
echo $editmodal;
echo $deletemodal;
echo '</div>';

