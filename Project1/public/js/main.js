
function order(field, order) {
    let url = new URL(window.location.href);
    let query_string = url.search;
    let search_params = new URLSearchParams(query_string);
    search_params.set('order', `${field} ${order}`);
    url.search = search_params.toString();
    let new_url = url.toString();
    window.location.href = new_url;
}

function create() {
    disable('.action.button');
    enable('#create .action.button');
    // TODO: stop editing current rows
}

function edit(rowId) {
    var editRow = $(`table tbody tr[data-row='${rowId}']`);
    console.log(editRow);
    var updateForm = $("#updateForm");
    editRow.find("td[data-value]").each(function(index, value) {
        console.log(`${index}: ${$(this).text()}`);
        console.log($(this).text());
    });
}

function remove(rowId) {
    console.log(rowId);
    let updateRow = $(`table tbody tr[data-row='${rowId}']`);
    let updateForm = $("#deleteForm");
    updateForm.find("input.data").each(function(index, value) {
        let field_name = $(this).attr("name");
        $(this).val(updateRow.find(`td[data-field='${field_name}']`).attr("data-value"));
    });
    $(`table tbody tr[data-row='${rowId}']`).find("td.data").each(function(index, value) {
        console.log(`${$(this).attr("data-field")}=${$(this).attr("data-value")}`);
        //console.log(`index: ${index}, value: ${$(this).text()}`);
        //console.log($(this).text());
    });
}

function disable(css) {
    $(css).addClass('disabled');
}

function enable(css) {
    $(css).removeClass('disabled');
}

function check_error() {
    let error_element = $("#error");
    let error_type = error_element.attr("value");
    let error_message = error_element.text();
    if (error_type && error_message) {
        $('body').toast({
            class: 'error',
            position: 'bottom center',
            displayTime: 5 * 1000,
            closeIcon: true,
            message: error_message
        });
    }
}

$(function() {
    check_error();

    $(".context .ui.sidebar").sidebar({
        context: $(".context .bottom.segment")
    }).sidebar("attach events", ".context .menu .item:first");
});
