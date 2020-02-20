var urlParams = new URLSearchParams(window.location.search);

if (urlParams.has('table')) {
    console.log('table: ' + urlParams.get('table'));
}
if (urlParams.has('edit')) {
    console.log('edit: ' + urlParams.get('edit'));
}

function push() {
    $('.ui.sidebar').sidebar('toggle');
}

function sort() {
    
}

function create() {
    disable('.action.button');
    enable('#create .action.button');
    // TODO: stop editing current rows
}

function edit(query) {
    console.log(query);
    window.location.href = "?" + query;
}

function remove(key) {
    console.log(key);
}

function disable(css) {
    $(css).addClass('disabled');
}

function enable(css) {
    $(css).removeClass('disabled');
}

$(function() {
    $("table tbody").find("tr").each(function(key, val) {
        $(this).find("td.data").each(function(key, val) {
            console.log(val.innerHTML);
        });
    });
});