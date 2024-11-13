$(document).ready(function () {
  document.querySelectorAll('.date-select').forEach((el) => {
    let settings = {
      hidePlaceholder: true,
      plugins: ['dropdown_input', 'no_backspace_delete'],
      closeAfterSelect: true,
      maxOptions: null,
      sortField: {
        field: "text",
        direction: "asc"
      },
      onItemAdd: function (value) {
        window.location.href = value;
      },
      onInitialize: function () {
        el.style.visibility = "visible";

      },
    };
    new TomSelect(el, settings);
  });

});