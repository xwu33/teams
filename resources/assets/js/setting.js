$( document ).ready(function () {
  $(".editOptionBtn").click(function(e){
    e.preventDefault();
    $("#editOptionModal #editOptionForm").attr('action', '/settings/' + $(this).data('optionid'));
    const option = $(this).parent().parent().children()[0].innerText;
    const value = $(this).parent().parent().children()[1].innerText;

    console.log(`'${option}' is '${value}'`);
    $("#editOptionModal #title").html(option);
    $("#editOptionModal #optionName").val(option);
    $("#editOptionModal #optionLabel").text(option);
    $("#editOptionModal #optionInput").val(value);
    $('#editOptionModal').modal();
  });

  $("#cancelEditOptionButton").click(function(e){
    e.preventDefault();
    $('#editOptionModal').modal('hide');
  });
});
