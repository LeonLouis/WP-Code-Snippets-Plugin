<h1>Settings</h1>
<form method="POST" action="options.php">
  <?php settings_fields( 'lcs_settings_group' ); ?>
  <?php do_settings_sections( 'lcs_settings_group' ); ?>
  <table class="form-table">
    <tr>
      <th><?php esc_html_e('Color Scheme', 'lcs-snippets'); ?></th>
      <td>
        <input name="lcs_color_scheme" id="lcs_color_scheme" class="lcs-color-field" type="text" value="<?php echo esc_html( lcs_get_color_scheme() ); ?>" data-default-color="#0697f2" />
      </td>
    </tr>
    <tr>
      <th><?php esc_html_e('Show Breadcrumbs', 'lcs-snippets'); ?></th>
      <td>
        <input name="lcs_show_breadcrumb" id="lcs_show_breadcrumb" type="checkbox" value="1" <?php echo esc_html( lcs_is_show_breadcrumb() ); ?>>
      </td>
    </tr>
    <tr>
      <th><?php esc_html_e('Snippets show at most', 'lcs-snippets'); ?></th>
      <td>
        <input name="lcs_num_show_snippets" id="lcs_num_show_snippets" type="number" step="1" min="1" value="<?php echo esc_html( lcs_get_num_snippets() ); ?>" class="small-text">
        <?php esc_html_e('snippets', 'lcs-snippets'); ?>
      </td>
    </tr>
  </table>
  <input class="primary button-primary" type="submit" name="submit" value="<?php esc_html_e('Save Settings', 'lcs-snippets' ); ?>" />
</form>