<?php
/**
 * Admin settings page.
 */

class DDVStoreSystem {
  /**
  * Holds the values to be used in the fields callbacks
  */
  private $options;

  /**
  * Start up
  */
  public function __construct() {
    add_action('admin_menu', array($this, 'add_plugin_page' ));
    add_action('admin_init', array($this, 'page_init'));
  }

  /**
  * Add options page
  */
  public function add_plugin_page() {
    // This page will be under "Settings"
    add_options_page(
      __('Store System Setting', 'ddv_store_system'),
      __('Store System', 'ddv_store_system'),
      'manage_options',
      'ddv-store-system-setting-admin',
      array($this, 'create_admin_page')
    );
  }

  /**
  * Options page callback
  */
  public function create_admin_page() {
    // Set class property
    $this->options = get_option('ddv_store_system_board_settings');

    ?>
    <div class="wrap">
      <h1 style="margin-bottom: 30px;"><?php echo __('Store System Setting', 'ddv_store_system'); ?></h1>

      <div class="store-system-import-form">
        <form enctype="multipart/form-data" action="" method="post" name="csv" id="csv">
          <div class="form-item">
            <strong><?php echo __("File import", 'ddv_store_system'); ?></strong><br>
            <input id="store_system" type="file" name="store_system" accept=".csv">
          </div>
          <br>
          <div class="form-item">
            <strong><?php echo __('Duplicate content', 'ddv_store_system'); ?></strong><br>
            <select id="store_duplicate" name="store_duplicate">
              <option value="update" selected="selected"><?php echo __('Update exited stores', 'ddv_store_system'); ?></option>
              <option value="create"><?php echo __('Create new stores', 'ddv_store_system'); ?></option>
            </select>
          </div>
          <br>
          <button id="btn-import-csv" class="button"><?php echo __('Import', 'ddv_store_system'); ?></button>
        </form>

        <div class="store-system-import-result-wrapper "><table class="store-system-import-result"><tbody></tbody></table></div>
      </div>
      <!-- <form method="post" action="options.php"> -->
      <?php
        // This prints out all hidden setting fields
        /*settings_fields('ddv_store_system_option_config');
        do_settings_sections('ddv-store-system-setting-admin');
        submit_button();*/
      ?>
      <!-- </form> -->
      <script type="text/javascript">
        (function($) {
          var ddv_store_system = function(e) {
            if(!confirm("<?php echo __('Are you sure import all Stores system from csv file?','ddv_store_system') ?>")){
              return false;
            } else {
              e.preventDefault();

              var $this = $(this);
              var wrapper = $this.parents('.store-system-import-form');
              var file_store_system = wrapper.find('input[name="store_system"]').val();
              // console.log(file_store_system);
              // return false;
              var data = new FormData(this);
              // console.log(data);
              // return false;

              data.append("action", "storesystemimport");

              $.ajax({
                type : "post",
                dataType : "json",
                url : ajaxurl,
                processData: false,
                contentType: false,
                cache: false,
                data : data,
                beforeSend: function() {
                  // $this.after('<span class="ccfdb-ajax-loader">Removing...</span>');
                  // $('.meassage-clean-bad-fields').empty();
                },
                success: function(response) {
                  console.log(response);
                  $('.store-system-import-result tbody').html(response.stores);
                  // $('.ccfdb-ajax-loader').remove();
                  // $('.meassage-clean-bad-fields').css('color', response.meassage_color).text(response.meassage);
                  // console.log(response.meassage);
                },
                error: function(response) {
                  console.log('error');
                }
              });
            }

            return false;
          }

          $(document).ready(function(){
            //$('#btn-import-csv').on('click', ddv_store_system);
            $('#csv').submit(ddv_store_system);
          });
        })(jQuery);
      </script>
    </div>
    <?php
  }

  /**
  * Register and add settings
  */
  public function page_init() {
    // register_setting('ddv_store_system_option_config', 'ddv_store_system_board_settings');

    // Facebook Account
    /*add_settings_section(
      'ddv_store_system_section_id', // ID
      '', // Title
      array( $this, 'print_section_info' ), // Callback
      'ddv-store-system-setting-admin' // Page
    );*/

    /*add_settings_field(
      'ddv_store_system_hidden_fields',
      __('Check it if you want hidden the bad fields', 'ddv_store_system'),
      array( $this, 'form_checkbox' ), // Callback
      'ddv-store-system-setting-admin', // Page
      'ddv_store_system_section_id',
      'ddv_store_system_hidden_fields'
    );*/
  }

  /**
  * Print the Section text
  */
  public function print_section_info() {}

  /**
  * Get the settings option array and print one of its values
  */
  public function form_checkbox($name) {
    $value = isset($this->options[$name]) ? 'checked' : '';
    printf('<input type="checkbox" id="form-id-%s" name="ddv_store_system_board_settings[%s]" value="1" %s />', $name, $name, $value);
  }
}
