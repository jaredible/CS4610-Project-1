const action_form = $("#action-form");

class Table {

    constructor(table_id, table_type) {
        this.table_id = table_id;
        this.table_type = table_type;

        this.table = $(`#${this.table_id}`);
        this.create_row = this.table.find("tbody tr:first");
        this.update_row = null;
    }

    order_by(field, order) {
        let url = new URL(window.location.href);
        let query_string = url.search;
        let search_params = new URLSearchParams(query_string);

        search_params.set('orderby', `${field} ${order}`);
        url.search = search_params.toString();

        let new_url = url.toString();
        window.location.href = new_url;
    }

    create_enter() {
        this.create_row.attr("style", "");

        disable_action_buttons_except(this.table, this.create_row);
    }

    create_submit() {
        let form_input_action = $("<input>");
        form_input_action.attr("type", "hidden");
        form_input_action.attr("name", "table-action");
        form_input_action.val("create");
        action_form.append(form_input_action);

        let form_input_type = $("<input>");
        form_input_type.attr("type", "hidden");
        form_input_type.attr("name", "table-type");
        form_input_type.val(this.table_type);
        action_form.append(form_input_type);

        this.create_row.find("td.input input").each(function(index, value) {
            let input_name = $(this).attr("name");
            let input_value = $(this).val();

            let form_input_element = $("<input>");
            form_input_element.attr("type", "hidden");
            form_input_element.attr("name", input_name);
            form_input_element.val(input_value);

            action_form.append(form_input_element);
        });

        action_form.submit();
    }

    create_leave() {
        this.create_row.attr("style", "display: none !important;");

        this.create_row.find("td.input input").each(function(index, value) {
            $(this).val("");
        });

        enable_action_buttons(this.table);
    }

    update_enter(row_id) {
        this.update_row = this.table.find(`tbody tr[data-row='${row_id}']`);

        //if (this.table_id === 'data-table') {
            this.update_row.find("td.data data").replaceWith(function() {
                let parent_td_element = $(this).parent();
                parent_td_element.removeClass("data");
                parent_td_element.addClass("input");
                let td_data_field = parent_td_element.attr("data-field");
                let td_data_value = parent_td_element.attr("data-value");
                
                let td_div_element = $("<div>");
                td_div_element.addClass("ui fluid input");

                let td_input_element = $("<input>");
                td_input_element.add
                td_input_element.attr("type", "text");
                td_input_element.val(td_data_value);
                td_input_element.attr("placeholder", to_title_case(td_data_field.replace("_", " ")));

                td_div_element.append(td_input_element);

                return td_div_element;
            });
        //} else if (this.table_id === 'structure-table') {
            //let data_type_dropdown = $("#data-type-dropdown .ui.selection.dropdown").clone(true);
            //this.update_row.find("td.data[data-field='TYPE'] data").replaceWith(data_type_dropdown);
            //$(".ui.dropdown").dropdown();

            //this.update_row.find("td.data data").replaceWith(function() {
            //    let parent_td_element = $(this).parent();
            //    parent_td_element.removeClass("data");
            //    parent_td_element.addClass("input");
            //    let td_data_field = parent_td_element.attr("data-field");
            //    let td_data_value = parent_td_element.attr("data-value");
                
            //    let td_div_element = $("<div>");
            //    td_div_element.addClass("ui fluid input");

            //    let td_input_element = $("<input>");
            //    td_input_element.add
            //    td_input_element.attr("type", "text");
            //    td_input_element.val(td_data_value);
            //    td_input_element.attr("placeholder", to_title_case(td_data_field.replace("_", " ")));

            //    td_div_element.append(td_input_element);

            //    return td_div_element;
            //});
        //}

        this.update_row.find("td:last .buttons:first").attr("style", "");
        this.update_row.find("td:last .buttons:last").attr("style", "display: none !important;");

        disable_action_buttons_except(this.table, this.update_row);
    }

    update_submit() {
        let form_input_action_input = $("<input>");
        form_input_action_input.attr("type", "hidden");
        form_input_action_input.attr("name", "table-action");
        form_input_action_input.val("update");
        action_form.append(form_input_action_input);

        let form_input_type = $("<input>");
        form_input_type.attr("type", "hidden");
        form_input_type.attr("name", "table-type");
        form_input_type.val(this.table_type);
        action_form.append(form_input_type);

        this.update_row.find("td.input div.ui.input input").each(function(index, value) {
            let parent_td_element = $(this).parent().parent();

            let td_data_field = parent_td_element.attr("data-field");
            let td_data_value = parent_td_element.attr("data-value");
            let input_value = $(this).val();

            let form_old_data_input_element = $("<input>");
            form_old_data_input_element.attr("type", "hidden");
            form_old_data_input_element.attr("name", `old_${td_data_field}`);
            form_old_data_input_element.val(td_data_value);

            let form_new_data_input_element = $("<input>");
            form_new_data_input_element.attr("type", "hidden");
            form_new_data_input_element.attr("name", `new_${td_data_field}`);
            form_new_data_input_element.val(input_value);

            action_form.append(form_old_data_input_element);
            action_form.append(form_new_data_input_element);
        });

        action_form.submit();
    }

    update_leave() {
        this.update_row.find("td.input div").replaceWith(function() {
            let parent_td_element = $(this).parent("td.input");
            parent_td_element.removeClass("input");
            parent_td_element.addClass("data");

            let td_data_value = parent_td_element.attr("data-value");
            
            let td_data_element = $("<data>");
            td_data_element.text(td_data_value);

            return td_data_element;
        });

        this.update_row.find("td:last .buttons:first").attr("style", "display: none !important;");
        this.update_row.find("td:last .buttons:last").attr("style", "");

        this.update_row = null;

        enable_action_buttons(this.table);
    }

    delete_submit(row_id) {
        let delete_row = this.table.find(`tbody tr[data-row='${row_id}']`);

        let form_input_action_input = $("<input>");
        form_input_action_input.attr("type", "hidden");
        form_input_action_input.attr("name", "table-action");
        form_input_action_input.val("delete");
        action_form.append(form_input_action_input);

        let form_input_type = $("<input>");
        form_input_type.attr("type", "hidden");
        form_input_type.attr("name", "table-type");
        form_input_type.val(this.table_type);
        action_form.append(form_input_type);

        delete_row.find(`td.data`).each(function(index, value) {
            let td_data_field = $(this).attr("data-field");
            let td_data_value = $(this).attr("data-value");

            let form_input_element = $("<input>");
            form_input_element.attr("type", "hidden");
            form_input_element.attr("name", td_data_field);
            form_input_element.val(td_data_value);

            action_form.append(form_input_element);
        });

        action_form.submit();
    }

}

function init_sidebar() {
    $(".context .ui.sidebar").sidebar({
        context: $(".context .bottom.segment")
    }).sidebar("attach events", ".context .menu .item:first");
}

function check_and_display_error() {
    let error_element = $("#error");
    let error_message = error_element.val();

    if (error_message) {
        display_error(error_message);
    }
}

function display_error(message) {
    $('body').toast({
        class: 'error',
        position: 'bottom center',
        displayTime: 5 * 1000,
        closeIcon: true,
        message: message
    });
}

function to_title_case(str) {
    str = str.toLowerCase().split(" ").map(function(word) {
        return word.replace(word[0], word[0].toUpperCase());
    });
    return str.join(" ");
}

const data_table = new Table("data-table", "data");
const structure_table = new Table("structure-table", "structure");

function login() {
    $(".mini.modal").modal("show");
}

function logout() {
    let form_input_type = $("<input>");
    form_input_type.attr("type", "hidden");
    form_input_type.attr("name", "log-action");
    form_input_type.val("logout");
    action_form.append(form_input_type);
    action_form.submit();
}

function disable_action_buttons_except(table, row) {
    $(table).find(".ui.action.button").each(function(index, value) {
        $(this).addClass("disabled");
    });
    $(row).find(".ui.action.button").removeClass("disabled");
}

function enable_action_buttons(table) {
    $(table).find(".ui.action.button").each(function(index, value) {
        $(this).removeClass("disabled");
    });
}

function show_help() {
    start_intro();
}

function start_intro() {
    let intro = introJs();

    intro.setOptions({
        showStepNumbers: false,
        steps: [
            {
                intro: "<h3>Welcome to University Portal</h3><p>An interface for our university's database!</p>",
                tooltipClass: "min-width-250"
            },
            {
                element: "#data-table thead",
                intro: "These are the column names of the table."
            },
            {
                element: "#data-table tbody",
                intro: "All the table's data."
            },
            {
                element: "#data-table tfoot",
                intro: "Here is where you can sort columns."
            },
            {
                element: "footer",
                intro: "<em data-emoji='sleeping'></em> Also made with no sleep."
            }
        ]
    });

    intro.onbeforechange(function() {
        // TODO: handle error toast
        //display_error("This is where error messages are displayed!");
        return true;
    });

    intro.start();
}

$(function() {
    init_sidebar();
    check_and_display_error();
});