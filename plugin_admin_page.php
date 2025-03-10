<?php
namespace StartklarElmentorFormsExtWidgets;


class StartklarPluginAdminPage {
    function __construct() {
        add_action( 'admin_menu', [$this,'startklar_admin_menu'] );
    }

    function startklar_admin_menu() {
        add_menu_page(
            __( 'Easy Elementor Addons', "startklar-elmentor-forms-extwidgets" ),
            __( 'Easy Elementor Addons', "startklar-elmentor-forms-extwidgets" ),
            'manage_options',
            'startklar-elmentor-forms-extwidgets',
            array( $this, 'startKlarElmentorFormsWidgetsPluginAdminPage' ),
            plugin_dir_url(__FILE__).'startklar_logo.png',
            100
        );
    }

    function startKlarElmentorFormsWidgetsPluginAdminPage(){

        load_theme_textdomain( 'startklar-elmentor-forms-extwidgets', __DIR__. '/languages' );
        $default_tab = null;
        if (isset($_GET['tab']) && !empty($_GET['tab'])) {
            $tab = sanitize_text_field($_GET['tab']);
        }else{ $tab = null;  }
        $tab = !empty($tab) ? $tab : $default_tab;
        ?>
        <!-- Our admin page content should all be inside .wrap -->
        <div class="wrap">
            <!-- Print the page title -->
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <p id="startklar_donate">
                <a href="https://www.paypal.com/donate/?hosted_button_id=J2FXPNSYGWLBE">
                    <button class="button button-primary">Please Donate to WEB-SHOP-HOSTING</button>
                </a>
                Help support WEB-SHOP-HOSTING by donating or sharing with your friends.
            </p>

            <style>
                #startklar_donate { color: #999; font-style: italic; }
                #startklar_donate a { text-decoration: none; }
                #startklar_donate button{ background: none; color: #ff2f00; border-color: #ff2f00; margin: 10px 0 10px; display: inline-block; vertical-align: baseline; }
                #startklar_donate button:hover { background: #ff2f00; color: #fff; }
                .burger-icon {
                    height: 100%;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    cursor: pointer;
                }

                .bar {
                    width: 15px;
                    height: 2px;
                    background-color: #727272;
                    margin: 1px 0;
                }
            </style>

            <!-- Here are our tabs -->
            <nav class="nav-tab-wrapper">
                <a href="?page=startklar-elmentor-forms-extwidgets" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">
                    <?php echo __( 'Phone Number Prefix Forms Field', "startklar-elmentor-forms-extwidgets" ) ?>
                </a>

                <a href="?page=startklar-elmentor-forms-extwidgets&tab=add_sett" class="nav-tab <?php if($tab==="add_sett"):?>nav-tab-active<?php endif; ?>">
                    <?php echo __( 'Additional settings', "startklar-elmentor-forms-extwidgets" ) ?>
                </a>
            </nav>

            <div class="tab-content">
                <?php switch($tab) :
                    case 'add_sett':
                        $this->additionalSettings();
                        break;

                    case 'elementor_forms_widget_setup':
                        $this->elementorFormsWidget_setup();
                        break;

                    default:
                        $this->elementorFormsWidget_setup();
                        break;

                endswitch; ?>
            </div>
        </div>
    <?php
    }

    function elementorFormsWidget_setup(){

        if (isset($_POST["country_arr"]) && is_array($_POST["country_arr"]) && count($_POST["country_arr"])){
            $cntr_arr = [];

            foreach ( $_POST["country_arr"] as $country ) {
                if (!isset($country["remove"])) {
                    $country_name_en = sanitize_text_field(stripslashes($country["country_name_en"]));
                    $icon = sanitize_text_field($country["icon"]);
                    $phone_code = sanitize_text_field($country["phone_code"]);

                    if (!empty($country_name_en)){
                        $cntr_arr[] = ["icon"=>$icon, "country_name_en"=>$country_name_en, "phone_code"=>$phone_code];
                    }
                }
            }

            $temp = json_encode($cntr_arr);
            $res = file_put_contents(__DIR__.'/assets/country_selector/countries_arr.json', $temp);
            if ($res === false) {
                $error = error_get_last();
                echo "File write error: " . $error['message'];
            } else {
                echo "File written successfully";
            }

        } else {
            $content = file_get_contents(__DIR__.'/assets/country_selector/countries_arr.json');
            $cntr_arr = json_decode($content, true);
        }

        foreach ($cntr_arr as $indx =>$itemp){
            unset ($cntr_arr[$indx]);
            $cntr_arr[$itemp["country_name_en"]] = $itemp;
        }
        $row_indx=1;
        ?>

        <div class="start_klar_admin_page_wrap">
            <form method="post">
                <div class="tablenav top">
                    <div class="alignleft actions bulkactions">
                        <input  style="min-width: 200px"  type="submit"  class="button-secondary"  value="<?php   echo __("Save", "startklar-elmentor-forms-extwidgets")  ?>"/>
                    </div>
                    <div>Drag and drop to reorder country options in the selector</div>
                    <br class="clear">
                </div>
                <table class="widefat striped">
                    <thead>
                    <tr>
                        <th><?php echo __("Delete", "startklar-elmentor-forms-extwidgets") ?></th>
                        <th><?php echo __("Country name", "startklar-elmentor-forms-extwidgets") ?></th>
                        <td></td>
                        <th><?php echo __("Flag file", "startklar-elmentor-forms-extwidgets") ?></th>
                        <th><?php echo __("Phone code", "startklar-elmentor-forms-extwidgets") ?></th>
                    </tr>
                    </thead>
                    <tbody class="list">
                    <tr class="list-item" draggable="true">
                        <td style="width:1%; white-space: nowrap;"><?php echo __("Insert new country", "startklar-elmentor-forms-extwidgets") ?></td>
                        <td  class="country_name">
                            <input type="text" name="country_arr[<?php echo esc_html($row_indx); ?>][country_name_en]" value=""/>
                        </td>
                        <td style="width:1%;"></td>
                        <td  style="width:1%;">
                            <input type="text" name="country_arr[<?php echo esc_html($row_indx); ?>][icon]" value=""/>
                        </td>
                        <td>
                            <input type="text" name="country_arr[<?php echo esc_html($row_indx); ?>][phone_code]" value=""/>
                        </td>
                        <td class="burger-icon">
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                        </td>
                    </tr>
                    <?php

                    $row_indx++;
                    foreach ($cntr_arr  as $country) { ?>
                    <tr class="list-item" draggable="true">
                        <td>
                            <input type="checkbox" name="country_arr[<?php echo esc_html($row_indx); ?>][remove]" value="1">
                        </td>
                        <td  class="country_name">
                            <input type="text" name="country_arr[<?php echo esc_html($row_indx); ?>][country_name_en]" value="<?php echo esc_html($country["country_name_en"]) ?>"/>
                        </td>
                        <td>
                            <?php
                                if (!empty($country["icon"])){
                                    $file=__DIR__."/assets/country_selector/".$country["icon"];

                                    if (file_exists($file) && is_file($file) && is_readable($file)){
                                        echo "<img draggable='false' src='".plugin_dir_url(__FILE__)."assets/country_selector".esc_url($country["icon"]) . "' style='width: 50px;'>";
                                    }
                                }
                            ?>
                        </td>
                        <td>
                            <input type="text" name="country_arr[<?php echo esc_html($row_indx); ?>][icon]" value="<?php echo isset($country["icon"]) ? esc_html($country["icon"]) : "" ?>"/>
                        </td>
                        <td>
                            <input type="text" name="country_arr[<?php echo esc_html($row_indx); ?>][phone_code]" value="<?php echo isset($country["phone_code"]) ? esc_html($country["phone_code"]) : "" ?>"/>
                        </td>
                        <td class="burger-icon">
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                        </td>
                    </tr>

                    <?php
                    $row_indx++;
                    } ?>
                    </tbody>
                </table>
                <div class="tablenav top">
                    <div class="alignleft actions bulkactions">
                        <input style="min-width: 200px" type="submit"  class="button-secondary"  value="<?php   echo __("Save", "startklar-elmentor-forms-extwidgets")  ?>"/>
                    </div>
                    <br class="clear">
                </div>
            </form>
        </div>

        <script defer>
            document.addEventListener("DOMContentLoaded", function () {
                const list = document.querySelector(".list");

                let draggedItem = null;

                list.addEventListener("dragstart", function (e) {
                    draggedItem = e.target;
                });

                list.addEventListener("dragover", function (e) {
                    e.preventDefault();
                });

                list.addEventListener("drop", function (e) {
                    e.preventDefault();
                    const target = e.target.closest(".list-item");

                    if (draggedItem && target && draggedItem !== target) {
                        const rect = target.getBoundingClientRect();
                        const next = draggedItem.compareDocumentPosition(target) & Node.DOCUMENT_POSITION_FOLLOWING;
                        list.insertBefore(draggedItem, next && target.nextSibling || target);
                    }

                    draggedItem.style.backgroundColor = "";
                });
            });
        </script>
        <style>
            .start_klar_admin_page_wrap td.country_name { width: 300px; }
            .start_klar_admin_page_wrap td.country_name input { width: 100%; }

        </style>
        <?php
    }

    function additionalSettings(){
        $options = get_option('startklar_options');
        if (isset($_POST["startklar_options"]) && is_array($_POST["startklar_options"]) && count($_POST["startklar_options"])){
            $options = $_POST["startklar_options"];
            if (!isset($options['blocking_php_file_upload'])){
                $options['blocking_php_file_upload'] = "";
            }
            update_option('startklar_options', $options);
        }
        if (!is_array($options) || !count($options) || empty($options) || !isset($options['blocking_php_file_upload'])){
            $options['blocking_php_file_upload'] = "true";
        }
        ?>
        <div class="start_klar_admin_page_wrap">
            <form  method="post" novalidate="novalidate">
                <input type="hidden"  name="startklar_options[update_at]"  value="<?php echo date("d-m-Y H:i:s") ?>">
                <div class="tablenav top">
                    <div class="alignleft actions bulkactions">
                        <input  style="min-width: 200px"  type="submit"  class="button-secondary"  value="<?php   echo __("Save", "startklar-elmentor-forms-extwidgets")  ?>"/>
                    </div>
                    <br class="clear">
                </div>
                <table class="widefat striped">
                    <thead>
                    </thead>
                    <tbody class="list">
                        <tr class="list-item" draggable="true">
                            <td style="width:1%; white-space: nowrap;"><?php echo __("Blocking PHP file upload", "startklar-elmentor-forms-extwidgets") ?></td>
                            <td><input type="checkbox" name="startklar_options[blocking_php_file_upload]" value="true"
                                    <?php checked( $options['blocking_php_file_upload'], "true" ); ?>>  </td>
                        </tr>
                    </tbody>
                </table>
                <div class="tablenav top">
                    <div class="alignleft actions bulkactions">
                        <input style="min-width: 200px" type="submit"  class="button-secondary"  value="<?php   echo __("Save", "startklar-elmentor-forms-extwidgets")  ?>"/>
                    </div>
                    <br class="clear">
                </div>
            </form>
        </div>  <?php
        }
}
