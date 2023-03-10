$(function () {                     

    $('.wc_loadimage').change(function () {
        var input = $(this);
        var target = $('.' + input.attr('name'));
        var fileDefault = target.attr('default');

        if (!input.val()) {
            target.fadeOut('fast', function () {
                $(this).attr('src', fileDefault).fadeIn('slow');
            });
            return false;
        }

        if (this.files && (this.files[0].type.match("image/jpeg") || this.files[0].type.match("image/png"))) {
            TriggerClose();
            var reader = new FileReader();
            reader.onload = function (e) {
                target.fadeOut('fast', function () {
                    $(this).attr('src', e.target.result).width('auto').fadeIn('fast');
                    //$(this).attr('src', e.target.result).width('100%').fadeIn('fast');
                });
            };
            reader.readAsDataURL(this.files[0]);
        } else {
            Trigger('<div class="trigger trigger_alert trigger_ajax"><b class="icon-warning">ERRO AO SELECIONAR:</b> O arquivo <b>' + this.files[0].name + '</b> não é válido! <b>Selecione uma imagem JPG ou PNG!</b></div>');
            target.fadeOut('fast', function () {
                $(this).attr('src', fileDefault).fadeIn('slow');
            });
            input.val('');
            return false;
        }
    });


    //############## GET CNPJ + Validando com function validarCNPJ
    $('.wc_getCnpj').on('blur', function () {
        if (!validarCNPJ(this.value)) {
            Trigger("<div class='trigger trigger_ajax trigger_error' >CNPJ Informado é inválido.</div>")
            $(".wc_getCnpj").focus();
            clearCnpjData();
        } else {
            var cnpj = $(this).val().replace('-', '').replace('/', '').replace('.', '').replace('.', '');

            if (cnpj.length === 14) {
                $.getJSON("https://www.receitaws.com.br/v1/cnpj/" + cnpj + '/?callback=?', function (data) {

                    if (!data.erro) {
                        $('#company_name').val(data.nome);///
                        $('#company_fantasy').val(data.fantasia);
                        $('#company_phone').val(data.telefone);///
                        $('#company_address').val(data.logradouro);
                        $('#company_number').val(data.numero);///
                        $('#company_complement').val(data.complemento);
                        $('#company_district').val(data.bairro);//
                        $('#company_mail').val(data.email);//
                        $('#company_cep').val(data.cep.replace('.', ''));
                        $('#company_city').val(data.municipio);///
                        $('#company_state').val(data.uf);//
                        $('#company_country').val('31');

                    } else {
                        Trigger("<div class='trigger trigger_ajax trigger_error' >CNPJ não encontrado na Receita Federal.</div>");
                        $(".wc_getCnpj").focus();
                        clearCnpjData();
                    }
                }, 'json');
            }

        }
    });

       //############## GET CEP
    $('#company_getCep').on('change', function () {
        var cep = $(this).val().replace('-', '').replace('.', '');
        if (cep.length === 8) {
            $.get("https://viacep.com.br/ws/" + cep + "/json", function (data) {
                if (!data.erro) {
                    $('.cep_district').val(data.bairro);
                    $('.cep_complement').val(data.complemento);
                    $('.cep_city').val(data.localidade);
                    $('.cep_address').val(data.logradouro);
                    $('.cep_uf').val(data.uf);
                }
            }, 'json');
        }
    });

    //############## MASK INPUT
    if ($('.formDate').length || $('.formTime').length || $('.formCep').length || $('.formCpf').length || $('.formPhone').length || $('.formCnpj').length || $('.formPercent').length || $('.formMoney').length) {

        $(".formDate").mask("99/99/9999");
        $(".formTime").mask("99/99/9999 99:99");
        $(".formCep").mask("99999-999");
        $(".formCpf").mask("999.999.999-99");
        $(".formCnpj").mask("99.999.999/9999-99");
        $(".formPercent").mask("99%");
        $(".formMoney").mask("#.##0,00", {reverse: true});

        var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        }, spOptions = {
            onKeyPress: function (val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };
        $('.formPhone').mask(SPMaskBehavior, spOptions);
    }

    /*############## DATEPICKER*/
   







   const BASE = window.location.origin + window.location.pathname.substring(window.location.pathname.indexOf('index.php'),0);
  

    // const BASE = 'http://localhost:8080/v7-Produto/v7';
    if ($('.jwc_datepicker').length) {
        $("head").append('<link rel="stylesheet" href="' + BASE + '/datepicker/datepicker.min.css">' );
       
        $.getScript(BASE + '/datepicker/datepicker.min.js');
        $.getScript(BASE + '/datepicker/datepicker.pt-BR.js', function () {
            $('.jwc_datepicker').datepicker({
                language: 'pt-BR',
                autoClose: true
                
            });
        });
    }

    if ($('.trigger-sucess').length) {
        setTimeout(function (){
            window.location.href= 'index.php?class=ProductList';
        }, 2000);
    }

    /*############## WC TAB  CUSTOM BY ALISSON*/
    $('html').on('click', '#company_tab', function () {
        if (!$(this).hasClass('wc_active')) {
            var WcTab = $(this).attr('href');
            $('#company_tab').removeClass('wc_active');
            $(this).addClass('wc_active');
            $('#company_tab_target.wc_active').fadeOut(200, function () {
                $(WcTab).fadeIn(200).addClass('wc_active');
            }).removeClass('wc_active');
        }
        if (!$(this).hasClass('wc_active_go')) {return false;}
    });

    /*############## WC ACCORDION*/
    $('#company_accordion').click(function () {
        $('#company_accordion_toogle_active').slideUp(200, function () {$(this).removeClass('wc_accordion_toogle_active');});
        $(this).find('#company_accordion_toogle:not(.wc_accordion_toogle_active)').slideToggle(200).addClass('wc_accordion_toogle_active');
    });
    $('#company_accordion div').click(function (e) {e.stopPropagation();});
    /*############## HIGHLIGHT*/
    if ($('*[class="brush: php;"]').length) {
        $("head").append('<link rel="stylesheet" href="' + BASE + '/_cdn/highlight.min.css">');
        $.getScript(BASE + '/_cdn/highlight.min.js', function () {$('*[class="brush: php;"]').each(function (i, block) {hljs.highlightBlock(block);});});
    }
    /*############## MODAL BOX*/
    if ($('*[rel*="shadowbox"]').length) {
        $("head").append('<link rel="stylesheet" href="' + BASE + '/_cdn/shadowbox/shadowbox.css">');
        $.getScript(BASE + '/_cdn/shadowbox/shadowbox.js', function () {Shadowbox.init();});
    }

    /*############### CAPITALIZE TEXT FORMS*/
    //Call function *ucfirts or ucwords* for capitalize input[type=text]
    $("form.form_capitalize textarea").keyup(function () {
        // force: true to lower case all letter except first
        let cp_value = ucfirst($(this).val(), true);
        $(this).val(cp_value);
    });

    $("form.form_capitalize input[type='text']").blur(function () {
        // to capitalize all word
        let cp_value = capitalize($(this).val());
        $(this).val(cp_value);
    });

    //Call function *lowercase* for lowercase input[type=email]
    $("form.form_capitalize input[type='email']").keyup(function () {
        let email = lowercase($(this).val());
        $(this).val(email);
    });


    //OTIMIZA TITULO DOS PRODUTOS (FACEBOOK/GOOGLE - LIMIT)
    $(".title_optimize").on("keyup click", function () {
        let limite = $(this).attr('maxlength');
        let caracteresDigitados = $(this).val().length;

        let caracteresRestantes = (parseInt(limite) - parseInt(caracteresDigitados));

        if (parseInt(caracteresDigitados) <= 65) {
            $(".chars").css('background-color', 'lawngreen').text(caracteresRestantes);
        } else {
            $(".chars").css('background-color', 'yellow').text(caracteresRestantes);
        }
    });

    //CONTADOR DE CARACTERES (SEO - LIMIT)
   /* $(".contador").on("keyup click", function () {
        let limite = 250;
        let caracteresDigitados = $(this).val().length;
        
        let caracteresRestantes = (parseInt(limite) - parseInt(caracteresDigitados));
        $(".caracteres").text(caracteresRestantes);
    });*/
});

//############## ON READY
$(document).ready(function () {
    //Coloca asterisco Vermelho nos Inputs required
    $(":not(input[typeof='radio'], input[typeof='checkbox']):required").prev().prepend('<b class="font_red">* </b>');

    //Muda a Classe do Pai dos input[type='radio']:: label.label_check (de fa fa-circle para  icon-radio-checked) caso esteja :checked
    $("form input[type='radio']").each(function (index, element) {
        if ($(this).is(':checked')) {
            $(this).parent().removeClass('icon-radio-unchecked').addClass(' icon-radio-checked');
            $(this).parent().siblings().not("span").removeClass(' icon-radio-checked').addClass('icon-radio-unchecked');
        }
    });

    //Muda a Classe do Pai dos input[type='checkbox']:: label.label_check (de fa fa-square para icon-checkbox-checked) caso esteja :checked
    $("form input[type='checkbox']").each(function (index, element) {
        if ($(this).is(':checked')) {
            $(this).parent().removeClass('icon-checkbox-unchecked').addClass('icon-checkbox-checked');
            //REMOVE A CLASSE DO BOTÃO ON/OFF
            if ($(this).parent().hasClass('switch')) {
                $(this).parent().removeClass('icon-checkbox-checked');
            }
        }
    });

    //OTIMIZA TITULO (FACEBOOK/GOOGLE - LIMIT)
    if ($(".title_optimize").length) {
        let limite = $(".title_optimize").attr('maxlength');
        let caracteresDigitados = $(".title_optimize").val().length;
        let caracteresRestantes = (parseInt(limite) - parseInt(caracteresDigitados));


        var limitChars = $(".contador").attr('maxlength');
        var counterChar = $(".contador").val();
        var resultChars = (parseInt(limitChars) - parseInt(counterChar.length));

        if (caracteresDigitados <= 65) {
            $(".chars").css('background-color', 'lawngreen').text(caracteresRestantes);
        } else {
            $(".chars").css('background-color', 'yellow').text(caracteresRestantes);
        }
        $(".caracteres").text(resultChars);

    }

});


/*TEXT FUNCTION*/
function ucfirst(str, force) {
    str = force ? str.toLowerCase() : str;
    return str.replace(/(\b)([a-zA-Z])/, function (firstLetter) {
        return firstLetter.toUpperCase();
    });
}

function abreviacao(s) {
    return /^([A-Z]\.)+$/.test(s);
}

function numeralRomano(s) {
    return /^M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/.test(s);
}

function capitalize(texto) {
    let prepos = ["da", "do", "das", "dos", "a", "e", "de"];
    return texto.split(' ') // quebra o texto em palavras
        .map((palavra) => { // para cada palavra
            if (abreviacao(palavra) || numeralRomano(palavra)) {
                return palavra;
            }
            palavra = palavra.toLowerCase();
            if (prepos.includes(palavra)) {
                return palavra;
            }
            return palavra.charAt(0).toUpperCase() + palavra.slice(1);
        })
        .join(' '); // junta as palavras novamente
}

function lowercase(str) {
    return str.toLowerCase();
}


function validarCNPJ(cnpj) {

    cnpj = cnpj.replace(/[^\d]+/g, '');

    if (cnpj == '') return false;

    if (cnpj.length != 14) return false;

    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" || cnpj == "11111111111111" || cnpj == "22222222222222" || cnpj == "33333333333333" || cnpj == "44444444444444" || cnpj == "55555555555555" || cnpj == "66666666666666" || cnpj == "77777777777777" || cnpj == "88888888888888" || cnpj == "99999999999999") return false;

    // Valida DVs
    tamanho = cnpj.length - 2
    numeros = cnpj.substring(0, tamanho);
    digitos = cnpj.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0)) return false;

    tamanho = tamanho + 1;
    numeros = cnpj.substring(0, tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1)) return false;

    return true;
}

function clearCnpjData() {
    $('#company_nome, .wc_fantasia, .wc_telefone, .wc_logradouro, .wc_numero, .wc_complemento, .wc_bairro, .wc_cep, .wc_municipio, .wc_uf').val('');
}

function Validation(field) {

    $("input:not([name='" + field + "'])").css({
        border: '1px solid green', boxShadow: 'none'
    });
    $("[name='" + field + "']").css({
        border: '1px solid red', boxShadow: '0px 0px 8px red'
    }).focus();
}

//############## MODAL MESSAGE
function Trigger(Message) {
    TriggerClose();
    $('body').before("<div class='trigger_modal'>" + Message + "</div>");

    $('.trigger_ajax').fadeIn().append("<div style='background-color: rgba(0,0,0,0.3); heigth: 2px; border-radius: 4px; padding: 4px; width: 1px; position: absolute; left: 0; bottom: 0;'></div>");

    $('.trigger_ajax div:last-child').animate({
        width: '+=98%'
    }, 5000, 'swing');

    setTimeout(function () {
        $('.trigger_ajax').slideUp('fast', function () {
            $(this).remove();
        });
    }, 5000);
}

function TriggerClose() {
    $('.trigger_ajax').fadeOut('fast', function () {
        $(this).remove();
    });
}

function ContagemCaracter() {
    var txtDC = document.getElementById('dc').value;
    var contador = document.getElementById('contaSpan').value;
    console.log(contador);
    console.log(txtDC);
}



