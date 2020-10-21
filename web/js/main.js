$(document).ready(function() {
    $('.flash').each(function(i, elem) {
        setTimeout(function() {
            $(elem).slideToggle();
        }, 2000);
    });
});