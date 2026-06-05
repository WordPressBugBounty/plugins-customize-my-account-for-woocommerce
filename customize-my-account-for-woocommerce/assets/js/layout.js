jQuery(document).ready(function($){

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

            var selectedclass = "";

            if (value == selectedValue) {
                selectedclass = "selected";
            }

            if(value !== '' && swatchImages[value]){

                swatches += `
                    <div class="sb-swatch ${selectedclass}"
                         data-value="${value}">
                        <img src="${swatchImages[value]}" alt="${value}">
                    </div>
                `;
            }
        });

        swatches += '</div>';

        $select.after(swatches).hide();
    });

    $(document).on('click', '.sb-swatch', function(){

        var value = $(this).data('value');
        var $wrapper = $(this).closest('.sb-image-swatches');
        var $select = $wrapper.prev('select');

        $wrapper.find('.sb-swatch').removeClass('selected');
        $(this).addClass('selected');

        $('span.wcmamtx_layout_template_override_no').text(value);

        $select.val(value).trigger('change');
    });


    var sidebarImages = {
        "01": wcmamtx_layout.sidebar1,
        "02": wcmamtx_layout.sidebar2,
    };

    $('select.wcmamtx_layout_sidebar_select').each(function(){

        var $select2 = $(this);
        var attributeName = $select2.attr('name');

        var swatches_sidebar = '<div class="sb-image-swatches-sidebar">';

        var selectedValue2 = $select2.val();

        $select2.find('option').each(function(){

            var value2 = $(this).val();

            var selectedclass2 = "";

            if (value2 == selectedValue2) {
                selectedclass2 = "selected";
            }

            if(value2 !== '' && sidebarImages[value2]){

                swatches_sidebar += `
                    <div class="sb-swatch-sidebar ${selectedclass2}"
                         data-value="${value2}">
                        <img src="${sidebarImages[value2]}" alt="${value2}">
                    </div>
                `;
            }
        });

        swatches_sidebar += '</div>';

        $select2.after(swatches_sidebar).hide();
    });

    $(document).on('click', '.sb-swatch-sidebar', function(){

        var value2 = $(this).data('value');
        var $wrapper2 = $(this).closest('.sb-image-swatches-sidebar');
        var $select2 = $wrapper2.prev('select');

        $wrapper2.find('.sb-swatch-sidebar').removeClass('selected');
        $(this).addClass('selected');

        $select2.val(value2).trigger('change');
    });

});