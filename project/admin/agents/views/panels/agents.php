<?php
echo '<div class="setup-left">'
. '<h4 class="mb5 text-light">Agents / Brokers Configuration</h4>'
. '<p>This section allows you to set the form fields for the agents / brokers interacting with the system</p>';

echo '</div>';

echo '<div class="setup-right">';
echo $agents_table;
echo $editmodal;
echo $deletemodal;
echo '</div>';

