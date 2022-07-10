jQuery(function($){
    $(document).ready(function() {
        $.each($("nav#sidebar ul.nav-main li.nav-main-item a.nav-main-link"), function(i, link) {
            if ($(link).prop('href') === window.location.href) {
                $(link).addClass('active');
            }
        });
    });
});