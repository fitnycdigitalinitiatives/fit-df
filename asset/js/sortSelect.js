$(document).ready(function () {
    let sortElement = document.getElementById("sort");
    let settings = {
        hidePlaceholder: true,
        plugins: ['dropdown_input', 'no_backspace_delete'],
        closeAfterSelect: true,
        maxOptions: null,
        onItemAdd: function (value) {
            let queryParams = new URLSearchParams(window.location.search);
            queryParams.set("sort", value);
            queryParams.set("page", 1);
            window.location.search = queryParams.toString();
        },
    };
    new TomSelect(sortElement, settings);

});