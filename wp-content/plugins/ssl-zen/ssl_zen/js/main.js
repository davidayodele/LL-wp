/*$(".btnNext").click(function() {
    if (form.valid()) {
        $('.progress-list > .active').addClass('completed').next('li').find('a').trigger('click');

        if ($('.progress-list > li.last-child').hasClass('active')) {
            $(".btnNext").text('SUBMIT');
        }

        $('.progress-list  li.completed i').removeClass('fa-circle').addClass('fa-check-circle');
    }
});*/


jQuery(document).ready(function () {
    jQuery("#frmstep1").validate({
            messages: {
                email: {
                    required: "Enter your Email",
                    email: "Please enter a valid email address.",
                }
            }
        }
    );
    jQuery('[data-toggle="tooltip"]').tooltip();
    jQuery('#fix_cloudflare').bootstrapToggle();

    jQuery(".btnNext").click(function () {
        if (jQuery('.ssl-zen-container form').valid()) {
            jQuery('body').prepend('<div class="ssl-loading-wrap"><div></div></div>');
        }
    });

});





