jQuery(document).ready(function($){
    
    var htlmtoinsert="";

    var swatchImages = {
        "01": wcmamtx_layout.image01,
        "02": wcmamtx_layout.image02,
        "03": wcmamtx_layout.image03,
        "04": wcmamtx_layout.image04,
    };

    $('select.wcmamtx_layout_design_select').each(function(){

        var $select = $(this);
        var attributeName = $select.attr('name');

        var swatches = '<div class="sb-image-swatches">';

        var selectedValue = $select.val();

        $select.find('option').each(function(){

            var value = $(this).val();

            var texttoinsert = $(this).attr("gtext");

            var disabledclass = "";

            if ($(this).prop('disabled')) {
                disabledclass = "wcmamtx_disabled_img";
                texttoinsert  += '('+wcmamtx_layout.proonly+')';
            }

            var selectedclass = "";

            var preview_link = '';

            var vpreview = $(this).attr("vpreview");


            if ((vpreview != "")) {

                preview_link = '(<a target="_blank" href="'+vpreview+'">'+wcmamtx_layout.previewtxt+'</a>)';

            }

            if (value == selectedValue) {
                selectedclass = "selected";
            }

            if(value !== '' && swatchImages[value]){

                

                htlmtoinsert = '<span class="wcmamtx_label_below_layout_images">'+texttoinsert+' '+preview_link+'</span>';

                swatches += `
                    <div class="sb-swatch ${selectedclass} ${disabledclass}"
                         data-value="${value}">
                        <img src="${swatchImages[value]}" alt="${value}">
                        ${htlmtoinsert}
                    </div>
                `;
            }
        });

        swatches += '</div>';

        $select.after(swatches).hide();
    });

    $(document).on('click', '.sb-swatch', function(){


        if ($(this).hasClass("wcmamtx_disabled_img")) {

            $('#wcmamtx_upgrade_modal').modal("show");

        } else {

            var value = $(this).data('value');
            var $wrapper = $(this).closest('.sb-image-swatches');
            var $select = $wrapper.prev('select');

            $wrapper.find('.sb-swatch').removeClass('selected');
            $(this).addClass('selected');

            $('span.wcmamtx_layout_template_override_no').text(value);

            $select.val(value).trigger('change');

        }

        
    });


    var sidebarImages = {
        "01": wcmamtx_layout.sidebar1,
        "02": wcmamtx_layout.sidebar2,
        "03": wcmamtx_layout.sidebar3,
    };

    $('select.wcmamtx_layout_sidebar_select').each(function(){

        var $select2 = $(this);
        var attributeName = $select2.attr('name');

        var swatches_sidebar = '<div class="sb-image-swatches-sidebar">';

        var selectedValue2 = $select2.val();

        $select2.find('option').each(function(){

            var value2 = $(this).val();

            var texttoinsert = $(this).attr("gtext");

            var disabledclass2 = "";

            if ($(this).prop('disabled')) {
                disabledclass2 = "wcmamtx_disabled_img"
                texttoinsert  += '('+wcmamtx_layout.proonly+')';
            }

            var selectedclass2 = "";

            if (value2 == selectedValue2) {
                selectedclass2 = "selected";
            }

            if(value2 !== '' && sidebarImages[value2]){

                

                htlmtoinsert = '<span class="wcmamtx_label_below_layout_images">'+texttoinsert+'</span>';

                swatches_sidebar += `
                    <div class="sb-swatch-sidebar ${selectedclass2} ${disabledclass2}"
                         data-value="${value2}">
                        <img src="${sidebarImages[value2]}" alt="${value2}">
                        ${htlmtoinsert}
                    </div>
                `;
            }
        });

        swatches_sidebar += '</div>';

        $select2.after(swatches_sidebar).hide();
    });

    $(document).on('click', '.sb-swatch-sidebar', function(){

        if ($(this).hasClass("wcmamtx_disabled_img")) {

            $('#wcmamtx_upgrade_modal').modal("show");

        } else {

            var value2 = $(this).data('value');
            var $wrapper2 = $(this).closest('.sb-image-swatches-sidebar');
            var $select2 = $wrapper2.prev('select');

            $wrapper2.find('.sb-swatch-sidebar').removeClass('selected');
            $(this).addClass('selected');

            $select2.val(value2).trigger('change');

        }
    });


    // Navigation bloxk


    var navigationImages = {
        "01": wcmamtx_layout.navigation1,
        "02": wcmamtx_layout.navigation2,
        "03": wcmamtx_layout.navigation3,
    };

    $('select.wcmamtx_layout_navigation_select').each(function(){

        var $select3 = $(this);
       

        var swatches3 = '<div class="sb-image-swatches-navigation">';

        var selectedValue3 = $select3.val();

        

        $select3.find('option').each(function(){

           

            var value3 = $(this).val();

            var selectedclass3 = "";

            if (value3 == selectedValue3) {
                selectedclass3 = "selected";
            }

            var texttoinsert = $(this).attr("gtext");

            var disabledclass3 = "";

            if ($(this).prop('disabled')) {
                disabledclass3 = "wcmamtx_disabled_img"
                texttoinsert  += '('+wcmamtx_layout.proonly+')';
            }

            var preview_link3 = '';

            var vpreview3 = $(this).attr("vpreview");


            if ((vpreview3 != "")) {

                preview_link3 = '(<a target="_blank" href="'+vpreview3+'">'+wcmamtx_layout.previewtxt+'</a>)';

            }

            if(value3 !== '' && navigationImages[value3]){

                

                htlmtoinsert = '<span class="wcmamtx_label_below_layout_images">'+texttoinsert+' '+preview_link3+'</span>';

                

                swatches3 += `
                    <div class="sb-swatch-navigation ${selectedclass3} ${disabledclass3}"
                         data-value="${value3}">
                        <img src="${navigationImages[value3]}" alt="${value3}">
                        ${htlmtoinsert}
                    </div>
                `;
            }


        });

        swatches3 += '</div>';

        $select3.after(swatches3).hide();
    });

    $(document).on('click', '.sb-swatch-navigation', function(){

        if ($(this).hasClass("wcmamtx_disabled_img")) {

            $('#wcmamtx_upgrade_modal').modal("show");


        } else {

            var value3 = $(this).data('value');
            var $wrapper3 = $(this).closest('.sb-image-swatches-navigation');
            var $select3 = $wrapper3.prev('select');

            $wrapper3.find('.sb-swatch-navigation').removeClass('selected');
            $(this).addClass('selected');

            $('span.wcmamtx_layout_navigation_override_no').text(value3);

            $select3.val(value3).trigger('change');

        }


    });


    // Order bloxk


    var orderImages = {
        "01": wcmamtx_layout.orders1,
        "02": wcmamtx_layout.orders2,
    };

    $('select.wcmamtx_layout_order_select').each(function(){

        var $select4 = $(this);
       

        var swatches4 = '<div class="sb-image-swatches-order">';

        var selectedValue4 = $select4.val();

        $select4.find('option').each(function(){

            var value4 = $(this).val();

            var selectedclass4 = "";

            if (value4 == selectedValue4) {
                selectedclass4 = "selected";
            }

            if(value4 !== '' && orderImages[value4]){

                var texttoinsert = $(this).attr("gtext");

                htlmtoinsert = '<span class="wcmamtx_label_below_layout_images">'+texttoinsert+'</span>';

                swatches4 += `
                    <div class="sb-swatch-order ${selectedclass4}"
                         data-value="${value4}">
                        <img src="${orderImages[value4]}" alt="${value4}">
                        ${htlmtoinsert}
                    </div>
                `;
            }
        });

        swatches4 += '</div>';

        $select4.after(swatches4).hide();
    });

    $(document).on('click', '.sb-swatch-order', function(){

        var value4 = $(this).data('value');
        var $wrapper4 = $(this).closest('.sb-image-swatches-order');
        var $select4 = $wrapper4.prev('select');

        $wrapper4.find('.sb-swatch-order').removeClass('selected');
        $(this).addClass('selected');

        $('span.wcmamtx_layout_order_override_no').text(value4);

        $select4.val(value4).trigger('change');
    });


    var downloadImages = {
        "01": wcmamtx_layout.download1,
        "02": wcmamtx_layout.download2,
    };

    $('select.wcmamtx_layout_download_select').each(function(){

        var $select5 = $(this);
       

        var swatches5 = '<div class="sb-image-swatches-download">';

        var selectedValue5 = $select5.val();

        $select5.find('option').each(function(){

            var value5 = $(this).val();

            var selectedclass5 = "";

            if (value5 == selectedValue5) {
                selectedclass5 = "selected";
            }

            if(value5 !== '' && downloadImages[value5]){

                var texttoinsert = $(this).attr("gtext");

                htlmtoinsert = '<span class="wcmamtx_label_below_layout_images">'+texttoinsert+'</span>';

                swatches5 += `
                    <div class="sb-swatch-download ${selectedclass5}"
                         data-value="${value5}">
                        <img src="${downloadImages[value5]}" alt="${value5}">
                        ${htlmtoinsert}
                    </div>
                `;
            }
        });

        swatches5 += '</div>';

        $select5.after(swatches5).hide();
    });

    $(document).on('click', '.sb-swatch-download', function(){

        var value5 = $(this).data('value');
        var $wrapper5 = $(this).closest('.sb-image-swatches-download');
        var $select5 = $wrapper5.prev('select');

        $wrapper5.find('.sb-swatch-download').removeClass('selected');
        $(this).addClass('selected');

        $('span.wcmamtx_layout_download_override_no').text(value5);

        $select5.val(value5).trigger('change');
    });

        var view_orderImages = {
        "01": wcmamtx_layout.view_order1,
        "02": wcmamtx_layout.view_order2,
    };

    $('select.wcmamtx_layout_view_order_select').each(function(){

        var $select6 = $(this);
       

        var swatches6 = '<div class="sb-image-swatches-view_order">';

        var selectedValue6 = $select6.val();

        $select6.find('option').each(function(){

            var value6 = $(this).val();

            var selectedclass6 = "";

            if (value6 == selectedValue6) {
                selectedclass6 = "selected";
            }

            if(value6 !== '' && view_orderImages[value6]){

                var texttoinsert = $(this).attr("gtext");

                htlmtoinsert = '<span class="wcmamtx_label_below_layout_images">'+texttoinsert+'</span>';

                swatches6 += `
                    <div class="sb-swatch-view_order ${selectedclass6}"
                         data-value="${value6}">
                        <img src="${view_orderImages[value6]}" alt="${value6}">
                        ${htlmtoinsert}
                    </div>
                `;
            }
        });

        swatches6 += '</div>';

        $select6.after(swatches6).hide();
    });

    $(document).on('click', '.sb-swatch-view_order', function(){

        var value6 = $(this).data('value');
        var $wrapper6 = $(this).closest('.sb-image-swatches-view_order');
        var $select6 = $wrapper6.prev('select');

        $wrapper6.find('.sb-swatch-view_order').removeClass('selected');
        $(this).addClass('selected');

        $('span.wcmamtx_layout_view_order_override_no').text(value6);

        $select6.val(value6).trigger('change');
    });



    //thankyou js handler

    var thankyouImages = {
        "01": wcmamtx_layout.thankyou1,
        "02": wcmamtx_layout.thankyou2,
    };

    $('select.wcmamtx_layout_thankyou_select').each(function(){

        var $select7 = $(this);
       

        var swatches7 = '<div class="sb-image-swatches-thankyou">';

        var selectedValue7 = $select7.val();

        $select7.find('option').each(function(){

            var value7 = $(this).val();

            var selectedclass7 = "";


            var disabledclass7 = "";

            var texttoinsert = $(this).attr("gtext");

            if ($(this).prop('disabled')) {
                disabledclass7 = "wcmamtx_disabled_img"
                texttoinsert  += '('+wcmamtx_layout.proonly+')';
            }


            var preview_link7 = '';

            var vpreview7 = $(this).attr("vpreview");


            if ((vpreview7 != "")) {

                preview_link7 = '(<a target="_blank" href="'+vpreview7+'">'+wcmamtx_layout.previewtxt+'</a>)';

            }

            if (value7 == selectedValue7) {
                selectedclass7 = "selected";
            }

            if(value7 !== '' && thankyouImages[value7]){

                

                htlmtoinsert = '<span class="wcmamtx_label_below_layout_images">'+texttoinsert+' '+preview_link7+'</span>';

                swatches7 += `
                    <div class="sb-swatch-thankyou ${selectedclass7} ${disabledclass7}"
                         data-value="${value7}">
                        <img src="${thankyouImages[value7]}" alt="${value7}">
                        ${htlmtoinsert}
                    </div>
                `;
            }
        });

        swatches7 += '</div>';

        $select7.after(swatches7).hide();
    });

    $(document).on('click', '.sb-swatch-thankyou', function(){


        if ($(this).hasClass("wcmamtx_disabled_img")) {

            $('#wcmamtx_upgrade_modal').modal("show");


        } else {

            var value7 = $(this).data('value');
            var $wrapper7 = $(this).closest('.sb-image-swatches-thankyou');
            var $select7 = $wrapper7.prev('select');

            $wrapper7.find('.sb-swatch-thankyou').removeClass('selected');
            $(this).addClass('selected');

            $('span.wcmamtx_layout_thankyou_override_no').text(value7);

            $select7.val(value7).trigger('change');


        }


    });


    //linkbox js handler

    var linkboxImages = {
        "01": wcmamtx_layout.linkbox1,
    };

    $('select.wcmamtx_layout_linkbox_select').each(function(){

        var $select8 = $(this);
       

        var swatches8 = '<div class="sb-image-swatches-linkbox">';

        var selectedValue8 = $select8.val();

        $select8.find('option').each(function(){

            var value8 = $(this).val();

            var selectedclass8 = "";


            var disabledclass8 = "";

            var texttoinsert = $(this).attr("gtext");

            if ($(this).prop('disabled')) {
                disabledclass8 = "wcmamtx_disabled_img"
                texttoinsert  += '('+wcmamtx_layout.proonly+')';
            }


            var preview_link8 = '';

            var vpreview8 = $(this).attr("vpreview");


            if ((vpreview8 != "")) {

                preview_link8 = '(<a target="_blank" href="'+vpreview8+'">'+wcmamtx_layout.previewtxt+'</a>)';

            }

            if (value8 == selectedValue8) {
                selectedclass8 = "selected";
            }

            if(value8 !== '' && linkboxImages[value8]){

                

                htlmtoinsert = '<span class="wcmamtx_label_below_layout_images">'+texttoinsert+' '+preview_link8+'</span>';

                swatches8 += `
                    <div class="sb-swatch-linkbox ${selectedclass8} ${disabledclass8}"
                         data-value="${value8}">
                        <img src="${linkboxImages[value8]}" alt="${value8}">
                        ${htlmtoinsert}
                    </div>
                `;
            }
        });

        swatches8 += '</div>';

        $select8.after(swatches8).hide();
    });

    $(document).on('click', '.sb-swatch-linkbox', function(){


        if ($(this).hasClass("wcmamtx_disabled_img")) {

            $('#wcmamtx_upgrade_modal').modal("show");


        } else {

            var value8 = $(this).data('value');
            var $wrapper8 = $(this).closest('.sb-image-swatches-linkbox');
            var $select8 = $wrapper8.prev('select');

            $wrapper8.find('.sb-swatch-linkbox').removeClass('selected');
            $(this).addClass('selected');

            $('span.wcmamtx_layout_linkbox_override_no').text(value8);

            $select8.val(value8).trigger('change');


        }


    });


    //profilebox js handler

    var profileboxImages = {
        "01": wcmamtx_layout.profilebox1,
    };

    $('select.wcmamtx_layout_profilebox_select').each(function(){

        var $select9 = $(this);
       

        var swatches9 = '<div class="sb-image-swatches-profilebox">';

        var selectedValue9 = $select9.val();

        $select9.find('option').each(function(){

            var value9 = $(this).val();

            var selectedclass9 = "";


            var disabledclass9 = "";

            var texttoinsert = $(this).attr("gtext");

            if ($(this).prop('disabled')) {
                disabledclass9 = "wcmamtx_disabled_img"
                texttoinsert  += '('+wcmamtx_layout.proonly+')';
            }


            var preview_link9 = '';

            var vpreview9 = $(this).attr("vpreview");


            if ((vpreview9 != "")) {

                preview_link9 = '(<a target="_blank" href="'+vpreview9+'">'+wcmamtx_layout.previewtxt+'</a>)';

            }

            if (value9 == selectedValue9) {
                selectedclass9 = "selected";
            }

            if(value9 !== '' && profileboxImages[value9]){

                

                htlmtoinsert = '<span class="wcmamtx_label_below_layout_images">'+texttoinsert+' '+preview_link9+'</span>';

                swatches9 += `
                    <div class="sb-swatch-profilebox ${selectedclass9} ${disabledclass9}"
                         data-value="${value9}">
                        <img src="${profileboxImages[value9]}" alt="${value9}">
                        ${htlmtoinsert}
                    </div>
                `;
            }
        });

        swatches9 += '</div>';

        $select9.after(swatches9).hide();
    });

    $(document).on('click', '.sb-swatch-profilebox', function(){


        if ($(this).hasClass("wcmamtx_disabled_img")) {

            $('#wcmamtx_upgrade_modal').modal("show");


        } else {

            var value9 = $(this).data('value');
            var $wrapper9 = $(this).closest('.sb-image-swatches-profilebox');
            var $select9 = $wrapper9.prev('select');

            $wrapper9.find('.sb-swatch-profilebox').removeClass('selected');
            $(this).addClass('selected');

            $('span.wcmamtx_layout_profilebox_override_no').text(value9);

            $select9.val(value9).trigger('change');


        }


    });


//orderpay js handler

    var orderpayImages = {
        "01": wcmamtx_layout.orderpay1,
        "02": wcmamtx_layout.orderpay2,
    };

    $('select.wcmamtx_layout_orderpay_select').each(function(){

        var $select10 = $(this);
       

        var swatches10 = '<div class="sb-image-swatches-orderpay">';

        var selectedValue10 = $select10.val();

        $select10.find('option').each(function(){

            var value10 = $(this).val();

            var selectedclass10 = "";


            var disabledclass10 = "";

            var texttoinsert = $(this).attr("gtext");

            if ($(this).prop('disabled')) {
                disabledclass10 = "wcmamtx_disabled_img"
                texttoinsert  += '('+wcmamtx_layout.proonly+')';
            }


            var preview_link10 = '';

            var vpreview10 = $(this).attr("vpreview");


            if ((vpreview10 != "")) {

                preview_link10 = '(<a target="_blank" href="'+vpreview10+'">'+wcmamtx_layout.previewtxt+'</a>)';

            }

            if (value10 == selectedValue10) {
                selectedclass10 = "selected";
            }

            if(value10 !== '' && orderpayImages[value10]){

                

                htlmtoinsert = '<span class="wcmamtx_label_below_layout_images">'+texttoinsert+' '+preview_link10+'</span>';

                swatches10 += `
                    <div class="sb-swatch-orderpay ${selectedclass10} ${disabledclass10}"
                         data-value="${value10}">
                        <img src="${orderpayImages[value10]}" alt="${value10}">
                        ${htlmtoinsert}
                    </div>
                `;
            }
        });

        swatches10 += '</div>';

        $select10.after(swatches10).hide();
    });

    $(document).on('click', '.sb-swatch-orderpay', function(){


        if ($(this).hasClass("wcmamtx_disabled_img")) {

            $('#wcmamtx_upgrade_modal').modal("show");


        } else {

            var value10 = $(this).data('value');
            var $wrapper10 = $(this).closest('.sb-image-swatches-orderpay');
            var $select10 = $wrapper10.prev('select');

            $wrapper10.find('.sb-swatch-orderpay').removeClass('selected');
            $(this).addClass('selected');

            $('span.wcmamtx_layout_orderpay_override_no').text(value10);

            $select10.val(value10).trigger('change');


        }


    });

    //



    $(".wcmamtx_template_override_a").on('click', function(event){

        event.preventDefault();

        $(this).parents('.wcmamtx-setting-card').find(".wcmamtx_template_override").show();

        return false;
    });


    $(".wcmamtx_layout_select_override").on('change', function(event){

        event.preventDefault();

        var selectedValue = $(this).val();

        if (selectedValue == "01") {
            $(this).parents(".wcmamtx-setting-card").find(".wcmamtx-card-body").show();
        } else {
            $(this).parents(".wcmamtx-setting-card").find(".wcmamtx-card-body").hide();
        }

        return false;
    });

    $('.wcmamtx_show_nav_header_widget').on("change",function() {
               
        if($(this).prop("checked")) {
            $('tr.nav_header_widget_tr').show();
        } else {
            $('tr.nav_header_widget_tr').hide();
        }
    });

    if ($(".wcmamtx_one_time_save").length > 0){

        $(".wcmamtx_one_time_save").trigger("click");

    }



});

jQuery(document).ready(function($){

    $('.wcmamtx_tab_link').on('click', function(e){

        e.preventDefault();

        var target = $(this).data('tab');

        $('.wcmamtx_tab_link').removeClass('active');
        $('.tab-pane').removeClass('active');

        $(this).addClass('active');
        $('#' + target).addClass('active');

    });

});

jQuery(document).ready(function($){

    $('.wcmamtx_tab_link').on('click', function(e){

        e.preventDefault();

        var target = $(this).data('tab');

        $('.wcmamtx_tab_link').removeClass('active');
        $(this).addClass('active');

        $('.tab-pane.active')
            .removeClass('active')
            .hide();

        $('#' + target)
            .addClass('active')
            .fadeIn(300);

    });

});