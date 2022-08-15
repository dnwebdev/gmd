$(document).ready(function(){
  var processing = false;
  var endreached = false;
  $(document).scroll(function(e){

    var scrollAmount = $(window).scrollTop();
    var documentHeight = $('#data_result').height();
    
    var scrollPercent = (scrollAmount / documentHeight) * 100;

    if(scrollPercent > 50) {         
      load_more();
    }

    function load_more() {
      if(!processing && !endreached){
        $('.preloader-wrapper').addClass('active');
        processing = true;
        $.getJSON($('#data_result').attr('load_more_url')+"/"+$('#data_result').attr('offset'), function(data){
            if(data.status == 200){
              if(data.data.view.length > 0){
                $('#data_result').append(data.data.view);
                $('#data_result').attr('offset',data.data.offset);
              }
              else{
                endreached = true;
              }
              processing = false;
            }

            $('.preloader-wrapper').removeClass('active');
        });

      }

    }

  });

});