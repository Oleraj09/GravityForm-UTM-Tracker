(function () {
    // Capture UTM params from URL and store in localStorage
    function getQueryParams() {
        let params = {};
        window.location.search.substring(1).split("&").forEach(function(param){
            let pair = param.split("=");
            if(pair.length===2) params[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
        });
        return params;
    }

    function storeUTMParams() {
        let utmParams = getQueryParams();
        let utmKeys = ["utm_id","utm_source","utm_medium","utm_campaign","utm_term","utm_content"];
        utmKeys.forEach(function(key){
            if(utmParams[key]) localStorage.setItem(key, utmParams[key]);
        });
    }

    storeUTMParams();
})();

(function($){
    var utmKeys = ["utm_id","utm_source","utm_medium","utm_campaign","utm_term","utm_content"];

    function populateUTM($form){
        utmKeys.forEach(function(key){
            var value = localStorage.getItem(key);
            if(value){
                var $field = $form.find('input[name^="input_"].' + key);
                if($field.length) $field.val(value).trigger("change");
                // Also add a hidden input for POST
                if($form.find('input[name="'+key+'"]').length===0){
                    $form.append('<input type="hidden" name="'+key+'" value="'+value+'">');
                } else {
                    $form.find('input[name="'+key+'"]').val(value);
                }
            }
        });
    }

    // Populate AJAX forms
    $(document).on('gform_post_render', function(event, formId){
        var $form = $('#gform_' + formId);
        if($form.length) populateUTM($form);
    });

    // Populate before normal submission
    $('.gform_wrapper form').on('submit', function(){
        populateUTM($(this));
    });

    // Clear localStorage after AJAX success
    window.gform_confirmation_loaded = function(formId){
        utmKeys.forEach(function(key){ localStorage.removeItem(key); });
    };

    // Clear localStorage after page reload (non-AJAX forms)
    $(window).on('load', function(){
        if($('.gform_confirmation_message').length) utmKeys.forEach(function(key){ localStorage.removeItem(key); });
    });

})(jQuery);
