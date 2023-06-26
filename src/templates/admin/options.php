<h1>
    Lamb Documentation.
</h1>

<?php

// General check for user permissions.
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient privileges to access this page.'));
}

?>

<div class="wrap">
    <form action="admin.php?page=atlas-documentation" method="post">
        <?php wp_nonce_field('rest_documentation_data'); ?>
        <input type="hidden" value="true" name="import_documentation" />
        Enter Site ID:
        <br/><br/>
        <input type="number" value="true" name="documentation_cat_id" />
        <?php submit_button('Import') ?>
    </form>

<?php
// Check whether the button has been pressed AND also check the nonce
if (isset($_POST['import_documentation']) && check_admin_referer('rest_documentation_data')) {
    do_action('atlas_import_docs', filter_input(INPUT_POST, 'documentation_cat_id', FILTER_VALIDATE_INT));
}
?>
</div>
