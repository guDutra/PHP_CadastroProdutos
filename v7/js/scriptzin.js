

$(".contador").on("keyup click", function () {
    let limite = $(this).attr('maxlength');
    let caracteresDigitados = $(this).val().length;
    
    let caracteresRestantes = (parseInt(limite) - parseInt(caracteresDigitados));
    $(".spanTXT").text(caracteresRestantes);
   /// console.log(caracteresRestantes);
});

$("#ptd_descont").on("change", function(){

    
    var precoTotal = $("#ptd_price_full").val();
    var IntporcentagemDesconto = $("#ptd_descont").val();
    var precoComDesconto = $("#ptd_price_alter");
    var IntprecoComDesconto = parseInt($("#ptd_price_alter").val());
    IntprecoComDesconto = IntporcentagemDesconto*precoTotal/100;
    var PrecoFinalDesc = precoTotal-IntprecoComDesconto;
    $('#ptd_price_alter').attr('value', PrecoFinalDesc);
});

$("#ptd_price_alter").on("change", function(){

    
    var precoTotal = $("#ptd_price_full").val();
    var IntporcentagemDesconto = $("#ptd_descont").val();
    var precoComDesconto = $("#ptd_price_alter");
    var IntprecoComDesconto = parseInt($("#ptd_price_alter").val());
    IntprecoComDesconto = IntporcentagemDesconto*precoTotal/100;
    var PrecoFinalDesc = precoTotal-IntprecoComDesconto;
    $('#ptd_price_alter').attr('value', PrecoFinalDesc);
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
 