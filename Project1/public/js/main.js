
class Table {

    constructor(table_id) {
        this.table_id = table_id;
    }

    order_by(field, order) {
    }

    create_enter() {
        $(`#${this.table_id} tbody tr:first`).attr("style", "");
    }

    create_submit() {
    }

    create_leave() {
        let create_row = $(`#${this.table_id}`);
        create_row.find("tbody tr:first").attr("style", "display: none !important;");
        createRow.find("td.input input").each(function(index, value) {
            $(this).val("");
        });
    }

    edit_enter() {
    }

    edit_submit() {
    }

    edit_leave() {
    }

}

const test = new Table("table");
test.create_enter();

function order(field, order) {
    let url = new URL(window.location.href);
    let query_string = url.search;
    let search_params = new URLSearchParams(query_string);
    search_params.set('order', `${field} ${order}`);
    url.search = search_params.toString();
    let new_url = url.toString();
    window.location.href = new_url;
}

function open_create() {
    let createRow = $("table tbody tr:first");
    createRow.show();
}

function cancel_create() {
    let createRow = $("table tbody tr:first");
    createRow.hide();
    clear_create_input();
}

function clear_create_input() {
    let createRow = $("table tbody tr:first");
    createRow.find("td.input input").each(function(index, value) {
        $(this).val("");
    });
}

function send_create() {
    let createRow = $(`table tbody tr:first`);
    let createForm = $("#createForm");
    createForm.find("input.data").each(function(index, value) {
        let field_name = $(this).attr("name");
        $(this).val(createRow.find("td.input input").val());
    });
    createForm.submit();
}

function open_edit(rowId) {
    var editRow = $(`table tbody tr[data-row='${rowId}']`);
    console.log(editRow);
    var updateForm = $("#updateForm");
    editRow.find("td[data-value]").each(function(index, value) {
        console.log(`${index}: ${$(this).text()}`);
        console.log($(this).text());
    });
}

function cancel_edit() {
    // TODO: only a single row can be edited at a time
}

function send_edit() {
}

function send_remove(rowId) {
    let deleteRow = $(`table tbody tr[data-row='${rowId}']`);
    let deleteForm = $("#deleteForm");
    deleteForm.find("input.data").each(function(index, value) {
        let field_name = $(this).attr("name");
        $(this).val(deleteRow.find(`td[data-field='${field_name}']`).attr("data-value"));
    });
    deleteForm.submit();
}

function init_sidebar() {
    $(".context .ui.sidebar").sidebar({
        context: $(".context .bottom.segment")
    }).sidebar("attach events", ".context .menu .item:first");
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
    init_sidebar();
    check_error();
});
