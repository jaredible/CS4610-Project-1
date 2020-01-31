var database = $("input[name='database']");
var table = $("input[name='table']");

function onChangeDatabase() {
    console.log("database changed");
    tryViewTable();
}

function onChangeTable() {
    console.log("table changed");
    tryViewTable();
}

function tryViewTable() {
    var databaseVal = database.val();
    var tableVal = table.val();
    console.log("Database: " + databaseVal);
    console.log("Table: " + tableVal);
    if (databaseVal && tableVal) {
        document.location.href = "index.php?database=" + databaseVal + "&table=" + tableVal;
    }
}

$(function() {
    $(".ui.dropdown").dropdown();
    $("table").tablesort();
    introJs().start();
});