$(document).ajaxStart(function () {
    $(document.body).css({'cursor': 'wait'});
}).ajaxStop(function () {
    $(document.body).css({'cursor': 'default'});
});