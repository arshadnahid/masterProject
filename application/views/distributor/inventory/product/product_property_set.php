<?php
/**
 * Created by PhpStorm.
 * User: Nahid
 * Date: 4/19/2020
 * Time: 4:43 PM
 */
?>
<style>

    .wrapper form input[type="text"], .wrapper form textarea {
        width: 65%;
        padding: 6px;
        display: inline-block;
        /*border: 1px solid #d4d4d4;
        border-bottom-right-radius: 5px;
        border-top-right-radius: 4px;
        border-bottom-left-radius: 5px;
        border-top-left-radius: 4px;

        line-height: 1.5em;
        !*margin-left:-5px;*!
        !* some box shadow sauce :D *!
        box-shadow: inset 0px 2px 2px #ececec;*/

    }

    .wrapper form input:focus {
        /* No outline on focus */
        outline: 0;
        /* a darker border ? */
        border: 1px solid #bbb;
    }

    .wrapper form input[type="checkbox"] {
        width: 20px;
    }

    .wrapper h3 {
        padding-left: 45px;
    }

    .funkyradio label {
        width: 26%;
        border-radius: 3px;
        border: 1px solid #D1D3D4;
        font-weight: normal;
    }

    .funkyradio input[type="checkbox"]:empty {
        display: none;
    }

    .funkyradio input[type="checkbox"]:empty ~ label {
        position: relative;
        line-height: 2.5em;
        text-indent: 3.25em;
        /*margin-top: 2em;*/
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .funkyradio input[type="checkbox"]:empty ~ label:before {
        position: absolute;
        display: block;
        top: 0;
        bottom: 0;
        left: 0;
        content: '';
        width: 2.5em;
        background: #D1D3D4;
        border-radius: 3px 0 0 3px;
    }

    .funkyradio input[type="checkbox"]:hover:not(:checked) ~ label {
        color: #888;
    }

    .funkyradio input[type="checkbox"]:hover:not(:checked) ~ label:before {
        content: '\2714';
        text-indent: .9em;
        color: #C2C2C2;
    }

    .funkyradio input[type="checkbox"]:checked ~ label {
        color: #777;
    }

    .funkyradio input[type="checkbox"]:checked ~ label:before {
        content: '\2714';
        text-indent: .9em;
        color: #333;
        background-color: #ccc;
    }

    .funkyradio input[type="checkbox"]:focus ~ label:before {
        box-shadow: 0 0 0 3px #999;
    }

    .funkyradio-default input[type="checkbox"]:checked ~ label:before {
        color: #333;
        background-color: #ccc;
    }

    .PropertyDiv {
        padding: 0;
        border: 2px solid #056D80;
        width: 20%;
        margin-right: 10px;
        float: left;
    }

    .tbl_prop_value {
        width: 100%;
    }

    .tbl_prop_value tr {

    }

    .tbl_prop_value th h3 {
        padding: 2px 5px;
        border: 1px solid #056D80;
        text-align: left;
        vertical-align: top;
    }

    .tbl_prop_value td {
        padding: 5px 12px;
        border: 2px solid #056D80;
        text-align: left;
        vertical-align: top;
    }

    .pv {
        float: left;
        padding: 2px 10px;
        border: 2px solid #CCCCCC;
        margin-right: 5px;
        margin-bottom: 5px;
        border-radius: 5px;
        height: 25px;
        min-width: 50px;
        text-align: center;
    }

    a.valclose, a.pkgclose {
        float: right;
        margin-top: -3px;
        margin-right: -11px;
        cursor: pointer;
        color: #F00;
        border: 1px solid #CCC;
        border-radius: 5px;
        /*background: #ff7c7c;*/
        background-color: #fff;
        font-size: 15px;
        font-weight: bold;
        display: inline-block;
        line-height: 0px;
        padding: 5px 2px 6px 3px;
    }

    a.boxclose {
        float: right;
        margin-top: 0px;
        margin-right: 0px;
        cursor: pointer;
        color: #F00;
        border: 0px solid #000;
        /*border-radius: 30px;*/
        background: #FFF;
        font-size: 15px;
        font-weight: bold;
        display: inline-block;
        line-height: 0px;
        padding: 6px 3px;
    }

    a.propertyclose {
        float: right;
        margin-top: 2px;
        margin-right: 2px;
        cursor: pointer;
        color: #F00;
        border: 0px solid #000;
        /*border-radius: 30px;*/
        background: #FFF;
        font-size: 15px;
        font-weight: bold;
        display: inline-block;
        line-height: 0px;
        padding: 6px 3px;
    }

    .boxclose:before, .valclose:before, .pkgclose:before, .propertyclose:before {
        content: "×";

    }

    .PacketDiv {
        padding: 0;
        border: 2px solid #056D80;
        width: 25%;
        float: left;
        margin: 20px 10px 0 0;
    }

    .panel-header {
        border-radius: 0;
    }

    .panel-header h3 {
        padding: 0 10px;
        text-align: center;
    }

    #racksdiv {
        border: 1px solid #ebebeb;
        min-height: 50px;
        margin-bottom: 20px;
        background-color: #fff;

    }

    .chkRacksDiv {
        width: 100px;
        float: left;
    }

    .divider {
        border-bottom: 1px solid #ebebeb;
        margin: 0 0 20px 20px;
    }


</style>
<form action="" id="frmre_prop" class="form-inline"
      name="frmre_prop" method="post">
    <fieldset>
        <legend>Property:</legend>
        <?php
        $getallproductProperty = $this->Common_model->get_data_list_by_single_column('product_property', '1', 1);
        $style = "";
        $chk = "";
        $custom = "";
        foreach ($getallproductProperty as $key => $value) {
            if ($key <= 4) {
                if ($value->is_show == 1) {
                    //$style="";
                    $chk = "checked='checked'";
                    $custom = $value->property_name_show;
                }


                ?>

                <div class="funkyradio" style="text-align: left;">
                    <div class="funkyradio-default">
                        <input class="chk" type="checkbox" <?php echo $chk; ?>
                               id="chk_<?php echo $value->property_id; ?>"
                               name="<?php echo $value->property_id; ?>" <?php echo $style; ?>>

                        <label style="width:40%;"
                               for="chk_<?php echo $value->property_id; ?>"><?php echo $value->property_name; ?></label>
                        Rename >
                        <input type="text" style="width:40%;" id="txt<?php echo $value->property_id; ?>"
                               name="txt<?php echo $value->property_id; ?>" class="form-control"
                               placeholder="Rename <?php echo $value->property_name; ?>" value="<?php echo $custom; ?>">
                    </div>

                </div>


                <?php
                $chk = "";
                $custom = "";
            }
        }
        ?>
    </fieldset>

    <div class="clearfix"></div><div class="clearfix"></div><div class="clearfix"></div><div class="clearfix"></div>
    <fieldset>
        <legend>Product Setup:</legend>
        <?php
        $getallproductProperty = $this->Common_model->get_data_list_by_single_column('product_property', '1', 1);
        $style = "";
        $chk = "";
        $custom = "";
        foreach ($getallproductProperty as $key => $value) {
            if ($key > 4) {
                if ($value->is_show == 1) {
                    //$style="";
                    $chk = "checked='checked'";
                    $custom = $value->property_name_show;
                }


                ?>

                <div class="funkyradio" style="text-align: left;">
                    <div class="funkyradio-default">
                        <input class="chk" type="checkbox" <?php echo $chk; ?>
                               id="chk_<?php echo $value->property_id; ?>"
                               name="<?php echo $value->property_id; ?>" <?php echo $style; ?>>

                        <label style="width:40%;"
                               for="chk_<?php echo $value->property_id; ?>"><?php echo $value->property_name; ?></label>
                        Rename >
                        <input type="text" style="width:40%;" id="txt<?php echo $value->property_id; ?>"
                               name="txt<?php echo $value->property_id; ?>" class="form-control"
                               placeholder="Rename <?php echo $value->property_name; ?>" value="<?php echo $custom; ?>">
                    </div>

                </div>


                <?php
                $chk = "";
                $custom = "";
            }
        }
        ?>
    </fieldset>
    <input style="margin-right: 10px;"
           type="submit" value="Submit"
           class="btn btn-green btn-success btnAdd"/>
</form>
<script type="text/javascript">

    $("input[type=checkbox].chk").each(function (index, element) {
        var txtName = $(this).attr('name');
        if (element.checked) {
            $("#txt" + txtName).prop("disabled", false);
        }
        else {
            $("#txt" + txtName).prop("disabled", true);
        }

    });
    $(".chk").click(function () {
        var txtName = $(this).attr('name');
        //if (txtName == "2" || txtName == "3") {
        if (this.checked) {
            $("#txt" + txtName).prop("disabled", false);
        }
        else {
            $("#txt" + txtName).prop("disabled", true);
        }
        //}
    });
</script>

