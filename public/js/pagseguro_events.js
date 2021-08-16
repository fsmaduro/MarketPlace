let cardNumber = document.querySelector('input[name=card_number]');
let spanBrand = document.querySelector('span.brand');
cardNumber.addEventListener('keyup', function(){
    if(cardNumber.value.length >= 6) {
        PagSeguroDirectPayment.getBrand({
            cardBin: cardNumber.value.substr(0,6),
            success: function(res) {
                let imgFlad = `<img src="https://stc.pagseguro.uol.com.br/public/img/payment-methods-flags/68x30/${res.brand.name}.png">`;
                spanBrand.innerHTML = imgFlad;

                document.querySelector('input[name=card_brand]').value = res.brand.name;

                getInstallments(amountTransaction, res.brand.name);
            },
            error: function(err) {
                console.log('Error: '+err);
            },
            complete: function(res) {
                // console.log('Complete: ',res);
            }
        });
    }
});

let submitButton = document.querySelector('button.processCheckout');

submitButton.addEventListener('click', function(event){
    event.preventDefault();

    PagSeguroDirectPayment.createCardToken({
        cardNumber: document.querySelector('input[name=card_number]'),
        brand: document.querySelector('input[name=card_brand]'),
        cvv: document.querySelector('input[name=card_cvv]'),
        expirationMonth: document.querySelector('input[name=card_month]'),
        expirationYear: document.querySelector('input[name=card_year]'),
        success: function(res) {
            proccessPayment(res.card.token);
        },
        error: function(err) {
            let msgError = 'Ops, algo errado com o seu pagamento!';
            for(let i in err.erros) {
                msgError = msgError+'<br>'+showErroMessages(errorsMapPagseguroJS(i))
            }
            document.querySelector('div.msg').innerHTML = msgError;
        },
    });
});
