<?php
echo '<div class="setup-left">'
    . '<h4 class="mb5 text-light">API Configurations</h4>'
    . '<p>This section allows you to set the form fields for each api token within the system</p>';

echo '</div>';

echo '<div class="setup-right">';
echo $tokens_setup;
echo $revoke_modal;
echo '</div>';
