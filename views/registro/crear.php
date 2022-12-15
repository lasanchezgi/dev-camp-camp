<main class="registro">
    <h2 class="registro__heading"> <?php echo $titulo; ?></h2>
    <p class="registro__descripcion">Elige tu plan</p>

    <div class="paquetes__grid">
        <div class="paquete">
            <h3 class="paquete__nombre">Pase gratis</h3>
            <ul class="paquete__lista">
                <li class="paquete__elemento">Acceso virtual a DevWebCamp</li>
            </ul>
            <p class="paquete__precio">$0</p>
            <form method="POST" action="/finalizar-registro/gratis">
                <input class="paquetes__submit" type="submit" value="Inscripción gratis">
            </form>
        </div>

        <div class="paquete">
            <h3 class="paquete__nombre">Pase presencial</h3>
            <ul class="paquete__lista">
                <li class="paquete__elemento">Acceso presencial a DevWebCamp</li>
                <li class="paquete__elemento">Pase por 2 días</li>
                <li class="paquete__elemento">Acceso a talleres y conferencias</li>
                <li class="paquete__elemento">Acceso a las grabaciones</li>
                <li class="paquete__elemento">Camisa del evento</li>
                <li class="paquete__elemento">Comida</li>
            </ul>
            <p class="paquete__precio">$200</p>

            <div id="smart-button-container">
                <div style="text-align: center;">
                    <div id="paypal-button-container"></div>
                </div>
            </div>

        </div>

        <div class="paquete">
            <h3 class="paquete__nombre">Pase virtual</h3>
            <ul class="paquete__lista">
                <li class="paquete__elemento">Acceso presencial a DevWebCamp</li>
                <li class="paquete__elemento">Pase por 2 días</li>
                <li class="paquete__elemento">Acceso a talleres y conferencias</li>
                <li class="paquete__elemento">Acceso a las grabaciones</li>
            </ul>
            <p class="paquete__precio">$50</p>

            <div id="smart-button-container">
                <div style="text-align: center;">
                    <div id="paypal-button-container-virtual"></div>
                </div>
            </div>

        </div>
    </div>
</main>



  <script src="https://www.paypal.com/sdk/js?client-id=AWt57EvaEIRGs4W9yD59Z7nhMhU8aj-HRyI_0WSKGSPLb6r5Pd0G64mid1dT2UpacKIZ5M29g5WQ36Cp&enable-funding=venmo&currency=USD" data-sdk-integration-source="button-factory"></script>
  <script>
    function initPayPalButton() {
      paypal.Buttons({
        style: {
          shape: 'rect',
          color: 'blue',
          layout: 'vertical',
          label: 'pay',
        },

        createOrder: function(data, actions) {
          return actions.order.create({
            purchase_units: [{"description":"1","amount":{"currency_code":"USD","value":238,"breakdown":{"item_total":{"currency_code":"USD","value":200},"shipping":{"currency_code":"USD","value":0},"tax_total":{"currency_code":"USD","value":38}}}}]
          });
        },

        onApprove: function(data, actions) {
          return actions.order.capture().then(function(orderData) {
            
            const datos = new FormData();
            datos.append('paquete_id', orderData.purchase_units[0].description);
            datos.append('pago_id', orderData.purchase_units[0].payments.captures[0].id);

            fetch('/finalizar-registro/pagar', {
                method: 'POST',
                body: datos
            })
            .then( respuesta => respuesta.json())
            .then(resultado => {
                if(resultado.resultado) {
                    actions.redirect('http://localhost:5000/finalizar-registro/conferencias');
                }
            })
        
          });
        },

        onError: function(err) {
          console.log(err);
        }
      }).render('#paypal-button-container');

      // Pase virtual
      paypal.Buttons({
        style: {
          shape: 'rect',
          color: 'blue',
          layout: 'vertical',
          label: 'pay',
        },

        createOrder: function(data, actions) {
          return actions.order.create({
            purchase_units: [{"description":"2","amount":{"currency_code":"USD","value":59.5,"breakdown":{"item_total":{"currency_code":"USD","value":50},"shipping":{"currency_code":"USD","value":0},"tax_total":{"currency_code":"USD","value":9.5}}}}]
          });
        },

        onApprove: function(data, actions) {
          return actions.order.capture().then(function(orderData) {

            const datos = new FormData();
            datos.append('paquete_id', orderData.purchase_units[0].description);
            datos.append('pago_id', orderData.purchase_units[0].payments.captures[0].id);

            fetch('/finalizar-registro/pagar', {
                method: 'POST',
                body: datos
            })
            .then( respuesta => respuesta.json())
            .then(resultado => {
                if(resultado.resultado) {
                    actions.redirect('http://localhost:5000/finalizar-registro/conferencias');
                }
            })

          });
        },

        onError: function(err) {
          console.log(err);
        }
      }).render('#paypal-button-container-virtual');
    }
    initPayPalButton();
  </script>