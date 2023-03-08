

$(".contador").on("keyup click", function () {
    let limite = $(this).attr('maxlength');
    let caracteresDigitados = $(this).val().length;
    
    let caracteresRestantes = (parseInt(limite) - parseInt(caracteresDigitados));
    $(".spanTXT").text(caracteresRestantes);
   /// console.log(caracteresRestantes);
});

/*$("form").on("submit", function (event)) {
    event.preventDefault();
    console.log($(this).serialize());
};*/


/*$("#ptd_image").on("change", function () { 

    console.log(this.val());
if(this.val().length>0) {

    console.log(this.val());

}


 });*/
 