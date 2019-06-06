$(document).ready(function() {
    $( "#advancedSearchBtn" ).click(function(e) {
        e.preventDefault();
        var cols = getSearchableCols();
        $("#searchRows").html(null);
        addRow(0,cols);
    });

    $( ".add-searchrow" ).click(function(e) {
        e.preventDefault();
        var count = $("#searchRows").children().length;
        var cols = getSearchableCols();
        addRow(count,cols);
    });
});

$("#searchRows").on('click', '.remove-searchrow', function(e) {
    e.preventDefault();
    removeRow(this);
});

function addRow(row, cols) {
    $.get( "/advancedSearch", { row: row, cols: cols }, function(data) {
        $("#searchRows").append(data);
    });
}


function removeRow(button) {
    var rows = $(button).parent().parent().parent();
    $(button).parent().parent().remove();
    $(rows).children().each(function(i, row) {
        $($(row).children()[1]).children().first().attr('name', 'search' + i);
        $($(row).children()[3]).children().first().attr('name', 'col' + i);
    });

}

function getSearchableCols() {
        var cols = [];
        $("#searchCols li").each(function() {
            if($(this).attr('__searchable') != 'true') {
              return true;
            }
            cols.push($(this).text());
        });

        return cols;
}
