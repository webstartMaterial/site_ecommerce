$(document).ready(function () {
    console.log('Hello World');

    $("select").change(function() {
        $(":submit").prop("disabled", false);
    });
});