<?php
#echo $base_url; 
#die;

$CI = & get_instance();
$CI->load->library('sma');
$this->data['lang'] = $CI->sma->get_lang_level('first');


$a = $_SESSION['flexi_auth']['group'];
foreach ($a as $k => $v) {
    $name = $v;
    $group_id = $k;
}

if (strpos($name, ' ') !== true) {
    $name = explode(' ', $name);
    $name = $name[0];
}
$col_class = $_SESSION['color_code'];
$col_class = $_SESSION['client']['color_code'];

// print_r($_SESSION);
// echo "subhma";
// print_r($_SESSION['color_code']);die();
//print_r($this->flexi_auth->is_admin());
//print_r($group_id);
?>
<div class="row">
    <nav role="navigation" class="navbar">

        <div class="navbar-header navbar-default">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse paddingLeft" id="myNavbar">
            <ul class="nav nav-justified">
                <?php
                if ($this->flexi_auth->is_admin()) {
                    if ($group_id == '9') {
                        ?>
                        <li id="dashboard">
                            <a href="<?php echo $base_url; ?>auth_admin/" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                if ($this->data['lang']['dashboard']['description'] != '') {
                                    echo $this->data['lang']['dashboard']['description'];
                                } else {
                                    echo "Dashboard";
                                }
                                ?></a>
                        </li>
                        <li>
                            <a href="<?php echo $base_url; ?>form_controller/inspector_assign" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                if ($this->data['lang']['inspection']['description'] != '') {
                                    echo $this->data['lang']['inspection']['description'];
                                } else {
                                    echo "Inspection";
                                }
                                ?></a>
                        </li>
                            <?php } elseif ($group_id == '10') { ?>
                        <li id="dashboard">
                            <a href="<?php echo $base_url; ?>auth_admin/" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                        if ($this->data['lang']['home']['description'] != '') {
                            echo $this->data['lang']['home']['description'];
                        } else {
                            echo "Home";
                        }
                        ?></a>
                        </li>
                    <?php } elseif ($group_id == '11') { ?>
                        <li id="dashboard">
                            <a href="<?php echo $base_url; ?>Client_view/" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                        if ($this->data['lang']['home']['description'] != '') {
                            echo $this->data['lang']['home']['description'];
                        } else {
                            echo "Home";
                        }
                        ?></a>
                        </li>
                    <?php } else { ?>
                        <li id="dashboard">
                            <a href="<?php echo $base_url; ?>auth_admin/" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                if ($this->data['lang']['dashboard']['description'] != '') {
                    echo $this->data['lang']['dashboard']['description'];
                } else {
                    echo "Dashboard";
                }
                        ?></a>
                        </li>
                    <?php
                    }
                } else {
                    ?>
                    <li>
                        <a href="<?php echo $base_url; ?>auth_public/" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                            if ($this->data['lang']['dashboard']['description'] != '') {
                                echo $this->data['lang']['dashboard']['description'];
                            } else {
                                echo "Dashboard";
                            }
                            ?></a>
                    </li>
                                <?php } ?>

<?php if (($group_id == '8')) { ?>
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"> <?php
                                    if ($this->data['lang']['inspection']['description'] != '') {
                                        echo $this->data['lang']['inspection']['description'];
                                    } else {
                                        echo "Inspection";
                                    }
                                    ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?php echo $base_url; ?>clientuser_dashboard/client_upcoming_data" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                    if ($this->data['lang']['upcoming']['description'] != '') {
                                        echo $this->data['lang']['upcoming']['description'];
                                    } else {
                                        echo "Upcoming";
                                    }
                                    ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $base_url; ?>clientuser_dashboard/client_past_data" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                        if ($this->data['lang']['past']['description'] != '') {
                            echo $this->data['lang']['past']['description'];
                        } else {
                            echo "Past";
                        }
                                    ?></a>
                            </li>
                        </ul>
                    </li>
                                <?php } else if ($group_id == '9') { ?>
                    <!--<li>
                            <a href="<?php echo $base_url; ?>form_controller/inspector_assign">Inspection</a>
                    </li>-->
                    <li>
                        <a href="<?php echo $base_url; ?>form_controller/manage_inspector" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                if ($this->data['lang']['manage_sites']['description'] != '') {
                                    echo $this->data['lang']['manage_sites']['description'];
                                } else {
                                    echo "Manage Sites";
                                }
                                    ?></a>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"> <?php
                                    if ($this->data['lang']['inspections']['description'] != '') {
                                        echo $this->data['lang']['inspections']['description'];
                                    } else {
                                        echo "Inspections";
                                    }
                                    ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?php echo $base_url; ?>form_controller/inspector_inspection" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                    if ($this->data['lang']['all']['description'] != '') {
                        echo $this->data['lang']['all']['description'];
                    } else {
                        echo "All";
                    }
                    ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $base_url; ?>form_controller/inspector_upcoming_data" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                if ($this->data['lang']['upcoming']['description'] != '') {
                    echo $this->data['lang']['upcoming']['description'];
                } else {
                    echo "Upcoming";
                }
                    ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $base_url; ?>form_controller/inspector_past_data" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                        if ($this->data['lang']['past']['description'] != '') {
                            echo $this->data['lang']['past']['description'];
                        } else {
                            echo "Past";
                        }
                    ?></a>
                            </li>
                        </ul>
                    </li>
                                <?php } ?>


                                <?php if (!$this->flexi_auth->is_logged_in_via_password()) { ?>
                    <li>
                        <a href="<?php echo $base_url; ?>auth" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php echo ($this->flexi_auth->is_logged_in()) ? 'Login via Password' : 'Login'; ?></a>
                    </li>
                                <?php } ?>



<?php if ($this->flexi_auth->is_admin()) { ?>

                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"> <?php
                                    if ($this->data['lang']['kare']['description'] != '') {
                                        echo $this->data['lang']['kare']['description'];
                                    } else {
                                        echo "Kare";
                                    }
                                    ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">

                            <li>
                                <a href="<?php echo $base_url; ?>subassets_kare/inspection_result_list" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                if ($this->data['lang']['inspection_result_type']['description'] != '') {
                                    echo $this->data['lang']['inspection_result_type']['description'];
                                } else {
                                    echo "Inspection/Result Type";
                                }
                                    ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $base_url; ?>subassets_kare/inspection_observation_list" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                if ($this->data['lang']['action_proposed']['description'] != '') {
                                    echo $this->data['lang']['action_proposed']['description'];
                                } else {
                                    echo "Action Proposed";
                                }
                                    ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $base_url; ?>subassets_kare/sub_assets_list" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                    if ($this->data['lang']['manage_sub_assets']['description'] != '') {
                                        echo $this->data['lang']['manage_sub_assets']['description'];
                                    } else {
                                        echo "Manage Sub-Assets";
                                    }
                                    ?></a>
                            </li> 
                            <li>
                                <a href="<?php echo $base_url; ?>manage_kare/assets_list" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                    if ($this->data['lang']['manage_assets']['description'] != '') {
                                        echo $this->data['lang']['manage_assets']['description'];
                                    } else {
                                        echo "Manage Assets";
                                    }
                                    ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $base_url; ?>manage_kare/asset_series_list" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                if ($this->data['lang']['manage_assets_series']['description'] != '') {
                                    echo $this->data['lang']['manage_assets_series']['description'];
                                } else {
                                    echo "Manage Assets Series";
                                }
                                    ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $base_url; ?>client_kare/client_view" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                if ($this->data['lang']['manage_client']['description'] != '') {
                                    echo $this->data['lang']['manage_client']['description'];
                                } else {
                                    echo "Manage Client";
                                }
                                    ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $base_url; ?>manage_kare/mdata_inspection" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                    if ($this->data['lang']['master_data_inspection']['description'] != '') {
                                        echo $this->data['lang']['master_data_inspection']['description'];
                                    } else {
                                        echo "Master Data Inspection";
                                    }
                                    ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $base_url; ?>sms_controller/sms_component_view" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                    if ($this->data['lang']['sms_component']['description'] != '') {
                                        echo $this->data['lang']['sms_component']['description'];
                                    } else {
                                        echo "SMS Component";
                                    }
                                    ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $base_url; ?>siteId_kare/siteId_master" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                    if ($this->data['lang']['manage_site_id']['description'] != '') {
                                        echo $this->data['lang']['manage_site_id']['description'];
                                    } else {
                                        echo "Manage Site ID";
                                    }
                                    ?></a>
                            </li>
    <?php if ($this->flexi_auth->is_privileged('View Inspector Form')) { ?>
                                <li>
                                    <a href="<?php echo $base_url; ?>manage_kare/assignInspector_list" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                        if ($this->data['lang']['assign_inspector']['description'] != '') {
                            echo $this->data['lang']['assign_inspector']['description'];
                        } else {
                            echo "Assign Inspector";
                        }
                        ?></a>
                                </li>
                                <li>
                                    <a href="<?php echo $base_url; ?>manage_kare/manageInspector_doc" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                        if ($this->data['lang']['manage_inspector']['description'] != '') {
                            echo $this->data['lang']['manage_inspector']['description'];
                        } else {
                            echo "Manage Inspector";
                        }
                        ?></a>
                                </li>
                                <li>
                                        <!--<a href="<?php //echo $base_url;?>assign_client_controller/assign_client">Assign Client</a>-->
                                    <a href="<?php echo $base_url; ?>assign_client_controller/assign_client_dealer" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                        if ($this->data['lang']['assign_client']['description'] != '') {
                            echo $this->data['lang']['assign_client']['description'];
                        } else {
                            echo "Assign Client";
                        }
                        ?></a>
                                </li>
                                <li>
                                    <a href="<?php echo $base_url; ?>assign_client_controller/assign_siteID" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                        if ($this->data['lang']['assign_site_id']['description'] != '') {
                            echo $this->data['lang']['assign_site_id']['description'];
                        } else {
                            echo "Assign Site ID";
                        }
                        ?></a>
                                </li>
                                <li>
                                    <a href="<?php echo $base_url; ?>productedit_controller/product_edit" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                        if ($this->data['lang']['product_edit']['description'] != '') {
                            echo $this->data['lang']['product_edit']['description'];
                        } else {
                            echo "Product Edit";
                        }
                        ?></a>
                                </li>
                    <?php } ?>

                            <li>
                                <a href="<?php echo $base_url; ?>subassets_kare/manage_stand_certificate" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                if ($this->data['lang']['manage_standard_certicate']['description'] != '') {
                    echo $this->data['lang']['manage_standard_certicate']['description'];
                } else {
                    echo "Manage Standard/Certicate";
                }
                    ?></a>
                            </li>           



                        </ul>
                    </li>


    <?php /*
      <li class="dropdown">
      <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"> <?php if ($this->data['lang']['reports']['description'] != '') {
      echo $this->data['lang']['reports']['description'];
      } else {
      echo "Reports";
      } ?> <b class="caret"></b></a>
      <ul class="dropdown-menu">
      <li>
      <a href="<?php echo $base_url; ?>form_controller/inspector" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php if ($this->data['lang']['insert_manual_reports']['description'] != '') {
      echo $this->data['lang']['insert_manual_reports']['description'];
      } else {
      echo "Insert Manual Reports";
      } ?></a>
      </li>
      <li>
      <a href="<?php echo $base_url; ?>inspector_inspection/" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php if ($this->data['lang']['view_inspector_reports']['description'] != '') {
      echo $this->data['lang']['view_inspector_reports']['description'];
      } else {
      echo "View Inspector Reports";
      } ?></a>
      </li>
      </ul>
      </li>
     * 
     */ ?>   

      <li class="dropdown">
      <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;">Asm<b class="caret"></b></a>
      <ul class="dropdown-menu">
      <li>
      <a href="<?php echo $base_url; ?>asm/index" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;">
      Asm Reports
      </a>
      </li>
      <li>
      <a href="<?php echo $base_url; ?>inspector_inspection/" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php if ($this->data['lang']['view_inspector_reports']['description'] != '') {
      echo $this->data['lang']['view_inspector_reports']['description'];
      } else {
      echo "View Inspector Reports";
      } ?></a>
      </li>
      </ul>
      </li>
                                  


                    <?php if ($group_id == 11 || $group_id == 10) { ?>
                        <li class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                        if ($this->data['lang']['product_portfolio']['description'] != '') {
                            echo $this->data['lang']['product_portfolio']['description'];
                        } else {
                            echo "Product Portfolio";
                        }
                        ?><b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo $base_url; ?>Client_view/" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                        if ($this->data['lang']['client_view']['description'] != '') {
                            echo $this->data['lang']['client_view']['description'];
                        } else {
                            echo "Client View";
                        }
                        ?></a>
                                </li>
                                <li>
                                    <a href="<?php echo $base_url; ?>infonet_left_menu/menus_category" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                        if ($this->data['lang']['infonet_products']['description'] != '') {
                            echo $this->data['lang']['infonet_products']['description'];
                        } else {
                            echo "Infonet Products";
                        }
                        ?></a>
                                </li>
                            </ul>		
                                    <?php } ?>

                                    <?php if ($group_id == 11 || $group_id == 10) { ?>
                        <li class="dropdown">
                            <a href="<?php echo $base_url; ?>infonet_details/data_on_demand" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                if ($this->data['lang']['data_on_demand']['description'] != '') {
                                    echo $this->data['lang']['data_on_demand']['description'];
                                } else {
                                    echo "Data on Demand";
                                }
                                ?></a>						
                        </li>
                                    <?php } ?>

                            <?php /* if ( $group_id == 2 || $group_id == 3 || $group_id == 10){ ?>
                              <li class="dropdown">
                              <!--<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Knowledge Database<b class="caret"></b></a>-->
                              <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?php if( $this->data['lang']['infonet']['description'] !='' ){ echo $this->data['lang']['infonet']['description']; }else{ echo "Infonet"; }  ?><b class="caret"></b></a>
                              <ul class="dropdown-menu">
                              <?php if ( $this->flexi_auth->is_privileged('Manage Infonet')){ ?>
                              <li>
                              <a href="<?php echo $base_url;?>category_controller/manage_category"><?php if( $this->data['lang']['manage_category']['description'] !='' ){ echo $this->data['lang']['manage_category']['description']; }else{ echo "Manage Category"; }  ?></a>
                              </li>
                              <li>
                              <a href="<?php echo $base_url;?>productCategory_controller/manage_product_categogy"><?php if( $this->data['lang']['manage_product']['description'] !='' ){ echo $this->data['lang']['manage_product']['description']; }else{ echo "Manage Product"; }  ?></a>
                              </li>
                              <li>
                              <a href="<?php echo $base_url;?>specification"><?php if( $this->data['lang']['specifications']['description'] !='' ){ echo $this->data['lang']['specifications']['description']; }else{ echo "Specifications"; }  ?></a>
                              </li>
                              <li>
                              <a href="<?php echo $base_url;?>specification/multi_uploads"><?php if( $this->data['lang']['multi_uploads']['description'] !='' ){ echo $this->data['lang']['multi_uploads']['description']; }else{ echo "Multi Uploads"; }  ?></a>
                              </li>
                              <!--
                              <li>
                              <a href="<?php //echo $base_url;?>Client_manual_data_controller/Client_manual_data">Client Manual Data</a>
                              </li>-->
                              <?php } ?>
                              <?php if ( $group_id == 2 || $group_id == 3){ ?>
                              <li>
                              <a href="<?php echo $base_url;?>Client_view/"><?php if( $this->data['lang']['client_view']['description'] !='' ){ echo $this->data['lang']['client_view']['description']; }else{ echo "Client View"; }  ?></a>
                              </li>
                              <li>
                              <a href="<?php echo $base_url;?>infonet_left_menu/menus_category"><?php if( $this->data['lang']['infonet_products']['description'] !='' ){ echo $this->data['lang']['infonet_products']['description']; }else{ echo "Infonet Products"; }  ?></a>
                              </li>
                              <li>
                              <a href="<?php echo $base_url;?>infonet_details/list_user_status/active"><?php if( $this->data['lang']['active_user']['description'] !='' ){ echo $this->data['lang']['active_user']['description']; }else{ echo "Active user"; }  ?></a>
                              </li>
                              <li>
                              <a href="<?php echo $base_url;?>infonet_details/list_user_status/inactive"><?php if( $this->data['lang']['inactive_user']['description'] !='' ){ echo $this->data['lang']['inactive_user']['description']; }else{ echo "Inactive User"; }  ?></a>
                              </li>
                              <?php } ?>
                              </ul>
                              </li>
                              <?php } */ ?>

                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                    if ($this->data['lang']['admin']['description'] != '') {
                                        echo $this->data['lang']['admin']['description'];
                                    } else {
                                        echo "Admin";
                                    }
                                    ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header"><?php
                                    if ($this->data['lang']['select_feature_to_manage']['description'] != '') {
                                        echo $this->data['lang']['select_feature_to_manage']['description'];
                                    } else {
                                        echo "Select Feature to Manage";
                                    }
                                    ?></li>

                                    <?php if ($this->flexi_auth->is_privileged('View Users') || $this->flexi_auth->is_privileged('Update Users') || $this->flexi_auth->is_privileged('Delete Users')) { ?>
                                <li>
                                    <a href="<?php echo $base_url; ?>auth_admin/manage_user_accounts" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                if ($this->data['lang']['user_accounts']['description'] != '') {
                                    echo $this->data['lang']['user_accounts']['description'];
                                } else {
                                    echo "User Accounts";
                                }
                                        ?></a>
                                </li>
    <?php } ?>
    <?php if ($this->flexi_auth->is_privileged('Update User Groups') || $this->flexi_auth->is_privileged('View User Groups') || $this->flexi_auth->is_privileged('Insert User Groups') || $this->flexi_auth->is_privileged('Delete User Groups')) { ?>
                                <li>
                                    <a href="<?php echo $base_url; ?>auth_admin/manage_user_groups" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                        if ($this->data['lang']['user_groups']['description'] != '') {
                            echo $this->data['lang']['user_groups']['description'];
                        } else {
                            echo "User Groups";
                        }
                        ?></a>			
                                </li>
                    <?php } ?>
                    <?php if ($this->flexi_auth->is_privileged('View Privileges') || $this->flexi_auth->is_privileged('Insert Privileges') || $this->flexi_auth->is_privileged('Update Privileges') || $this->flexi_auth->is_privileged('Delete Privileges')) { ?>
                                <li>
                                    <a href="<?php echo $base_url; ?>auth_admin/manage_privileges" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                        if ($this->data['lang']['user_privileges']['description'] != '') {
                            echo $this->data['lang']['user_privileges']['description'];
                        } else {
                            echo "User Privileges";
                        }
                        ?></a>			
                                </li>
                            <?php } ?>
                                
                         <?php /*       
                            <li>
                                <a href="<?php echo $base_url; ?>auth_admin/list_user_status/active" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                        if ($this->data['lang']['active_users']['description'] != '') {
                            echo $this->data['lang']['active_users']['description'];
                        } else {
                            echo "Active Users";
                        }
                            ?></a>
                            </li>	
                            <li>
                                <a href="<?php echo $base_url; ?>auth_admin/list_user_status/inactive" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                if ($this->data['lang']['inactive_users']['description'] != '') {
                                    echo $this->data['lang']['inactive_users']['description'];
                                } else {
                                    echo "Inactive Users";
                                }
                            ?></a>
                            </li>
                          */?>  
                            
                            <li>
                                <a href="<?php echo $base_url; ?>auth_admin/delete_unactivated_users" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                if ($this->data['lang']['unactivated_users']['description'] != '') {
                    echo $this->data['lang']['unactivated_users']['description'];
                } else {
                    echo "Unactivated Users";
                }
                            ?></a>
                            </li>
                            
                            <li>
                                <a href="<?php echo $base_url; ?>auth_admin/failed_login_users" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                        if ($this->data['lang']['failed_logins']['description'] != '') {
                            echo $this->data['lang']['failed_logins']['description'];
                        } else {
                            echo "Failed Logins";
                        }
                            ?></a>			
                            </li>
                            <li>
                                <a href="<?php echo $base_url; ?>auth_admin/password_change" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                    if ($this->data['lang']['change_user_password']['description'] != '') {
                                        echo $this->data['lang']['change_user_password']['description'];
                                    } else {
                                        echo "Change User Password";
                                    }
                                    ?></a>			
                            </li>
                            <li>
                                <a href="<?php echo $base_url; ?>auth_admin/logs" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                if ($this->data['lang']['logs']['description'] != '') {
                                    echo $this->data['lang']['logs']['description'];
                                } else {
                                    echo "Logs";
                                }
                                    ?></a>			
                            </li>
                            <li>
                                <a href="<?php echo $base_url; ?>auth_admin/imei_no" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                if ($this->data['lang']['approve_imei_no']['description'] != '') {
                                    echo $this->data['lang']['approve_imei_no']['description'];
                                } else {
                                    echo "Approve IMEI No.";
                                }
                                    ?></a>
                            </li>

                            <li>
                                <a href="<?php echo $base_url; ?>language_controller/index" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
                                    if ($this->data['lang']['language_level']['description'] != '') {
                                        echo $this->data['lang']['language_level']['description'];
                                    } else {
                                        echo "Language Level";
                                    }
                                    ?></a>
                            </li>

                        </ul>		
                    </li>
                        <?php } // end: is_Admin 
                        ?>

                                <?php /* if ( $group_id == 9){ ?>
                                  <li class="dropdown">
                                  <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?php if( $this->data['lang']['manage_kare']['description'] !='' ){ echo $this->data['lang']['manage_kare']['description']; }else{ echo "Manage Kare"; }  ?><b class="caret"></b></a>
                                  <ul class="dropdown-menu">
                                  <li>
                                  <a href="<?php echo $base_url;?>form_controller/assign_list"><?php if( $this->data['lang']['assign_list']['description'] !='' ){ echo $this->data['lang']['assign_list']['description']; }else{ echo "Assign List"; }  ?></a>
                                  </li>
                                  </ul>
                                  </li>
                                  <?php } */ ?>

<?php if ($group_id == 10) { ?>
                    <li class="dropdown">
                        <!--<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Admin Priviledges <b class="caret"></b></a>-->
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
    if ($this->data['lang']['admin_priviledges']['description'] != '') {
        echo $this->data['lang']['admin_priviledges']['description'];
    } else {
        echo "Admin Priviledges";
    }
    ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?php echo $base_url; ?>infonet_details/list_user_status/active" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
    if ($this->data['lang']['active_user']['description'] != '') {
        echo $this->data['lang']['active_user']['description'];
    } else {
        echo "Active User";
    }
    ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $base_url; ?>infonet_details/list_user_status/inactive" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
    if ($this->data['lang']['inactive_user']['description'] != '') {
        echo $this->data['lang']['inactive_user']['description'];
    } else {
        echo "Inactive User";
    }
    ?></a>
                            </li>
                        </ul>
                    </li>
<?php } ?>

                <li class="dropdown">
                    <!--<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">My Profile <b class="caret"></b></a>-->
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"> <?php
if ($this->data['lang']['profile']['description'] != '') {
    echo $this->data['lang']['profile']['description'];
} else {
    echo "Profile";
}
?> <b class="caret"></b></a>	
                    <ul class="dropdown-menu">
                        <li class="dropdown-header"><?php
if ($this->data['lang']['select']['description'] != '') {
    echo $this->data['lang']['select']['description'];
} else {
    echo "Select";
}
?></li>
                        <li>
                            <a href="<?php echo $base_url; ?>auth_public/update_account" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
if ($this->data['lang']['account_details']['description'] != '') {
    echo $this->data['lang']['account_details']['description'];
} else {
    echo "Account Details";
}
?></a>
                        </li>
                        <li>
                            <a href="<?php echo $base_url; ?>auth_public/update_email" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
if ($this->data['lang']['email_address']['description'] != '') {
    echo $this->data['lang']['email_address']['description'];
} else {
    echo "Email Address";
}
?></a>
                        </li>
                        <li>
                            <a href="<?php echo $base_url; ?>auth_public/change_password" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
if ($this->data['lang']['change_password']['description'] != '') {
    echo $this->data['lang']['change_password']['description'];
} else {
    echo "Change Password";
}
?></a>
                        </li>
                        <!--
                        <li>
                                <a href="<?php //echo $base_url;  ?>auth_public/manage_address_book">Address Book</a>
                        </li>-->

<?php if (!$this->flexi_auth->is_logged_in()) { ?>
                            <li>
                                <a href="<?php echo $base_url; ?>auth/register_account" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
    if ($this->data['lang']['register']['description'] != '') {
        echo $this->data['lang']['register']['description'];
    } else {
        echo "Register";
    }
    ?></a>
                            </li>
<?php } else { ?>
    <?php if ($group_id == 11 || $group_id == 10) { ?>
                                <li>
                                    <a href="<?php echo $base_url; ?>infonet_details/logout" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
        if ($this->data['lang']['logout']['description'] != '') {
            echo $this->data['lang']['logout']['description'];
        } else {
            echo "Logout";
        }
        ?></a>
                                </li>
    <?php } else { ?>
                                <li>
                                    <a href="<?php echo $base_url; ?>auth/logout" style="background-color: <?php echo $col_class; ?> !important; border: 1px solid <?php echo $col_class; ?> !important;"><?php
        if ($this->data['lang']['logout']['description'] != '') {
            echo $this->data['lang']['logout']['description'];
        } else {
            echo "Logout";
        }
        ?></a>
                                </li>
    <?php } ?>		

<?php } ?>	

                    </ul>		
                </li>
            </ul>
        </div>

    </nav>
</div>