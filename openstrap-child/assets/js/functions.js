jQuery(document).ready(function($) {

  $(".diary-click").on("click", function() {
    var cellData = $(this).data();
    var jsonData = {
      'action'      : 'diary_click',
      'dog_id'      : cellData.dog,
      'event_id'    : cellData.event,
      'event_date'  : cellData.date
    };

    if (cellData.status == "Y"){
      $(this).removeClass('success');
      $(this).removeClass('text-success');
      $(this).addClass('danger');
      $(this).addClass('text-danger');
      $(this).data('status', "N");
      $(this).html('<i class="fa fa-times" aria-hidden="true"></i>');
      jsonData.status = 'N';
    }
    else if (cellData.status == "N"){
      $(this).removeClass('danger');
      $(this).removeClass('text-danger');
      $(this).data('status', "?");
      $(this).html('&nbsp;');
      jsonData.status = '?';
    }
    else {
      $(this).addClass('success');
      $(this).addClass('text-success');
      $(this).data('status', "Y");
      $(this).html('<i class="fa fa-check" aria-hidden="true"></i>');
      jsonData.status = 'Y';
    }

    $.ajax({
          type : "post",
          dataType : "json",
          url : CambsAjax.ajax_url,
          data : jsonData,
          success : function(results) {
            // console.log(results);
          }
    });
  });
});