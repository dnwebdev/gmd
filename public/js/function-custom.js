window.$ = jQuery;
function readURL(input, imageSrc){
  if(input.files && input.files[0]){
    let reader = new FileReader();

    reader.onload = function(e){
      $(document).find(imageSrc).attr('src', e.target.result);
      // $(document).find('img.step-5').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
  }
}