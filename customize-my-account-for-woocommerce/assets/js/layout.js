jQuery(document).ready(function($){

    var swatchImages = {
        "01": wcmamtx_layout.image01,
        "02": wcmamtx_layout.image02,
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

        $select.val(value).trigger('change');
    });

});