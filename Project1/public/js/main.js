
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
    //console.log(query);
    var editRow = $(`table tbody tr[data-row='${rowId}']`);
    console.log(editRow);
    var updateForm = $("#updateForm");
    editRow.find("td[data-value]").each(function(index, value) {
        console.log(`${index}: ${$(this).text()}`);
        console.log($(this).text());
    });
    //window.location.href = "?" + query;
}

function remove(rowId) {
    console.log(rowId);
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
    
    $("table tbody tr[data-row]").each(function(index1, value1) {
        $(this).find("td[data-value]").each(function(index2, value2) {
            console.log(`${index2} | ${$(this).text()}`);
        });
        console.log('----------');
    });
});
