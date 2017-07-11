<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>
    paypal.Button.render({

        env: 'sandbox',

        commit: true,

        payment: function() {
            return paypal.request.post("{{ $create_url }}")
                .then(function(res) {
                    return res.id;
                });
        },

        onAuthorize: function(data, actions) {
           var data = {
               payment_id: data.paymentID,
               payer_id: data.payerID
           };

           return paypal.request.post("{{ $execute_url }}", data)
               .then(function (res) {
                   if (res.result === true) {
                       window.location.replace("{{ $redirect_url }}");
                   } else {
                       window.alert('There was an error with the payment');
                   }
               });
       }

   }, '#{{ $button_id }}');
</script>
