$(document).ready(function () {
    console.log('Hello World Backoffice');

    $("select").change(function() {
        // déclencher le submit du formulaire si la valeur de mon select change
        $(this).parent().trigger("submit");
    });
});