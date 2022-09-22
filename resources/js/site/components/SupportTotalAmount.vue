<template>
    <div>

				<div class="payment-process">
 
		<span @click="PrevousCard()" id="Pcard" class="showingdetails">Payment with Previous Credit Card</span>
<br>
		<span @click="NewCard()" id="Ncard" class="showingdetails">Payment with New Credit Card</span>

					</div>


				<div id="testcheck1" style="height: auto;">
        <main>
        <div id="myFormx" style=" padding:5px;  width: 100%;">
        <iframe id="pay-formx"  style="height: 500px !important;"></iframe>
        </div>
     	  </main>
				</div>


			  

<div id="testcheck2" style="height: auto;">
                  <main>
                <div id="myForm" style="padding:5px; ">
                    <iframe id="pay-form" style="height: 500px !important;"></iframe>
                </div>
               </main>
</div>
    					 
						<h1 @click="apiostdemo()" id="datasaver" style="display:none;">click here</h1>

            <!-- <span id="my-submit-button"   style="padding: 5px;height: 40px; border-radius: 3px;">
						<span id="my-submit-button-text">fffff</span>
					</span> -->
				


					<!-- <div v-if="this.tmails">
<h1>Han bhai</h1> 
					</div> -->
	  
					<!-- <h1 @click="oooo()">click here</h1>
                    <h1   id="emailarryhere">{{this.emailm}}</h1> 
                    <h1 id="tokenarryhere"> </h1>
        <h1   id="tokenvalueget"> </h1> -->



<!-- <h1>{{this.total}}</h1> -->

		<!-- <span @click="oooo()" > zzzz </span> -->
    
       
    </div>
</template>

<script>

export default {

 
  props: {
		    total: Number,
    emailm: String,
  },

setup(props){
localStorage.setItem('YourItem', props.emailm)
localStorage.setItem('OderAmount', props.total)
			 
 

},

  data() {
    return {
    tokenK:any,
    tmail:any,
		tamount:any,
		tgettokent:any,
		UserExperience:any,
		emailIDt:any,
    };
  },
 

 created(){
			this.tmail = localStorage.getItem('YourItem');
this.tokenK = localStorage.getItem('TokenEatz');
this.tgettokent = localStorage.getItem('UserRegisterToken');
		console.log('we find this email', this.tmail);
 	fetch(`http://127.0.0.1:8000/api/findemailtoken/${this.tmail}`)
  .then((response) => response.json())
  .then((json) => {

      localStorage.setItem('UserRegisterToken', json.token);
				
			 
					
	});
 
 },


  mounted() {
 
		this.tgettokent = localStorage.getItem('UserRegisterToken');
		this.tmail = localStorage.getItem('YourItem');
		this.tokenK = localStorage.getItem('TokenEatz');
		this.tamount = localStorage.getItem('OderAmount');
		this.emailIDt = localStorage.getItem('emailID');

		console.log("All",this.tgettokent,'|',this.tmail,this.tokenK|this.tamount);
	
     this.ScriptRender();
       this.ProcessChecker();
        this.oooo(); 
				},

   

  methods: {

     ProcessChecker(){
						if(this.tgettokent){
							document.getElementById('testcheck1').style.display = 'block';
					document.getElementById('testcheck2').style.display = 'none';

					document.getElementById('my-submit-buttonx').style.display = 'block';
															document.getElementById('my-submit-button').style.display = 'none';
					

					document.getElementById('Pcard').style.display = 'none';
					document.getElementById('Ncard').style.display = 'block';
						}else{
									document.getElementById('testcheck1').style.display = 'none';
					document.getElementById('testcheck2').style.display = 'block';

					document.getElementById('my-submit-buttonx').style.display = 'none';

										document.getElementById('my-submit-button').style.display = 'block';


document.getElementById('Pcard').style.display = 'none';
					document.getElementById('Ncard').style.display = 'none';
						}
		 },


			PrevousCard(){
				document.getElementById('testcheck1').style.display = 'block';
					document.getElementById('testcheck2').style.display = 'none';

					document.getElementById('my-submit-buttonx').style.display = 'block';
															document.getElementById('my-submit-button').style.display = 'none';
					

					document.getElementById('Pcard').style.display = 'none';
					document.getElementById('Ncard').style.display = 'block';

			},
			NewCard(){
		document.getElementById('testcheck1').style.display = 'none';
					document.getElementById('testcheck2').style.display = 'block';

					document.getElementById('my-submit-buttonx').style.display = 'none';

										document.getElementById('my-submit-button').style.display = 'block';


document.getElementById('Pcard').style.display = 'block';
					document.getElementById('Ncard').style.display = 'none';
			},



		oooo(){

						if (this.tgettokent) {
							
	 			async function HMAC(key, message){
          const g = str => new Uint8Array([...unescape(encodeURIComponent(str))].map(c => c.charCodeAt(0))),
          k = g(key),
          m = g(message),
          c = await crypto.subtle.importKey('raw', k, { name: 'HMAC', hash: 'SHA-256' },true, ['sign']),
          s = await crypto.subtle.sign('HMAC', c, m);
          return [...new Uint8Array(s)].map(b => b.toString(16).padStart(2, '0')).join('');
        }
  
        /* Values that will be needed for generating the HMAC */
        let host_domain = "http://127.0.0.1:8000";
        //let qor_form_id = "frm_e67ba9b701de11ed87940aac2e024c3e"; // test form_id from a saved embedded form template
        let qor_form_id = "frm_b903c7670f6b11ed9d150aac2e024c3e"; // test form_id from a saved embedded form template - clean eatz
        let qor_form_id2 = "frm_6adbb4c30e5f11ed9d150aac2e024c3e"; // test form_id from a saved embedded form template
        let qor_app_key = "T6554252567241061980"; // test app-key
        let qor_client_key = "01dffeb784c64d098c8c691ea589eb82"; // test client-key
        let qor_mid = "887728203"; // test MID
  
        const payfields = {};
  
        payfields["width"] = '520'+'px'; // sets payment form DIV width and passed along to payment form -> 400px is single column, 520px (+) grid rendered
  
        document.getElementById("myFormx").style.width = payfields["width"];
  
        const params = (new URL(location)).searchParams;
        
        switch(params.get('option')) {
          
  
          case '5':
  
            /** use this form to send a payment token and verify the CC CVV for the payment attempt ***/
  
            payfields["timestamp"] = Math.floor(Date.now() / 1000); // Current Unix timestamp -> used for auto-reload
            payfields["payment-cvv"] = 1; 
            payfields["domain"] = host_domain; // Domain of the website that will host this embeddedpayment form
            payfields["app-key"] = qor_app_key;
            payfields["client-key"] = qor_client_key;
            payfields["mid"] = qor_mid;
            payfields["account"] = this.tgettokent; // send a tokenized account to be charged (???? needs to be encrypted)
            //payfields["account"] = "t:111141$7XTnFmYb"; // send a tokenized account to be updated
            payfields["amount"] = "10.10"; // amount of order or shopping cart to be charged
            payfields["orderid"] = "oid-" + Math.floor(Math.random() * 100000) + 1000;; // unique order id - REPLACE with our oredr id
            payfields["button-submit"] = 0;
            // payfields["button-text"] = "Place Order (verify CVV)"; // set the submit button text
  
            break;
  
         
  
          default:
  
             payfields["timestamp"] = Math.floor(Date.now() / 1000); // Current Unix timestamp -> used for auto-reload
            payfields["payment-cvv"] = 1; 
            payfields["domain"] = host_domain; // Domain of the website that will host this embeddedpayment form
            payfields["app-key"] = qor_app_key;
            payfields["client-key"] = qor_client_key;
            payfields["mid"] = qor_mid;
            payfields["account"] = this.tgettokent; // send a tokenized account to be charged (???? needs to be encrypted)
            //payfields["account"] = "t:111141$7XTnFmYb"; // send a tokenized account to be updated
            payfields["amount"] = '10.10'; // amount of order or shopping cart to be charged
            payfields["orderid"] = "oid-" + Math.floor(Math.random() * 100000) + 1000;; // unique order id - REPLACE with our oredr id
            payfields["button-submit"] = 0;
            payfields["button-text"] = "Place Order (verify CVV)"; // set the submit button text
  
        }
  
        let data_to_hash = Object.values(payfields).join(''); // implode values only
  
        // hash and build hmac
        HMAC(qor_client_key, data_to_hash)
           .then(function(hash) { 
             document.getElementById("pay-formx").setAttribute("data-hmac-hmacsha256", hash); 
  
             for (const [key, value] of Object.entries(payfields)) {
               document.getElementById("pay-formx").setAttribute("data-hmac-"+key, value); 
            }
  
             var paymentForm = new QorPaymentForm( "pay-formx","https://secure.qorcommerce.io");
  
            /***
              * define your own submit button 
              * When generating the iframe, send the attribute button-submit = 0.
                This will hide the payment forms default submit button.
              • to trigger a submit of the payment form, use the paymentForm object's
                submitPayment method
            ***/
            
            var mySubmitButton = document.getElementById("my-submit-buttonx");
            mySubmitButton.addEventListener("click", function() {
                paymentForm.submitPayment();
              });
            
  
            paymentForm.onSubmit(function(response) {
              if (response.code === 'approved') {
                // alert( "Received an approval: " + response.onSuccess );
                // record the transaction, save the token if needed and finish order/sale
                console.log(response.onSuccess);
                const obj = JSON.parse(response.onSuccess); //parse the onSuccess response
                console.log(obj.transaction_id);
								 var btnsaver	= document.getElementById('datasaver')
								btnsaver.click();
								// setTimeout(window.location.assign("http://127.0.0.1:8000/thank-you.html"), 5000);
                window.location.assign("thank-you");
              } else {
                // alert( "Received a decline: " + response.onError );
                console.log(response.onError);
                paymentForm.enableSubmitButton();
              }
            });
  
            paymentForm.request();
           } );
						}else{
							console.log('i not receving the token')
						}

		},

 
        apiostdemo(){
					console.log('this is work');
 		this.tgettokent = localStorage.getItem('UserRegisterToken');
		this.tokenK = localStorage.getItem('TokenEatz');
		this.emailIDt = localStorage.getItem('emailID');


	 	fetch('http://127.0.0.1:8000/api/testpostccom', {
  method: 'POST',
  body: JSON.stringify({
    email: this.tmail,
    token: this.tokenK,
  }),
  headers: {
    'Content-type': 'application/json; charset=UTF-8',
  },
})
  .then((response) => response.json())
  .then((json) =>{
						if(json.data === "send"){
									console.log('data send hogya');
								 
						}
		console.log('this create api',json)
		
		});
						
  
        },



postPost() {


  },

     
     

  ScriptRender(){
          

			
				 
           
			 async function HMAC(key, message){
					const g = str => new Uint8Array([...unescape(encodeURIComponent(str))].map(c => c.charCodeAt(0))),
					k = g(key),
					m = g(message),
					c = await crypto.subtle.importKey('raw', k, { name: 'HMAC', hash: 'SHA-256' },true, ['sign']),
					s = await crypto.subtle.sign('HMAC', c, m);
					return [...new Uint8Array(s)].map(b => b.toString(16).padStart(2, '0')).join('');
				}
	
				/* Values that will be needed for generating the HMAC */
				let host_domain = "http://127.0.0.1:8000";
				// let qor_form_id = "frm_e67ba9b701de11ed87940aac2e024c3e"; // test form_id from a saved embedded form template
				let qor_form_id = "frm_b903c7670f6b11ed9d150aac2e024c3e"; // test form_id from a saved embedded form template - clean eatz
				// let qor_form_id = "frm_6adbb4c30e5f11ed9d150aac2e024c3e"; // test form_id from a saved embedded form template
				let qor_app_key = "T6554252567241061980"; // test app-key
				let qor_client_key = "01dffeb784c64d098c8c691ea589eb82"; // test client-key
				let qor_mid = "887728203"; // test MID
	
				const payfields = {};
	
				payfields["width"] = '520'+'px'; // sets payment form DIV width and passed along to payment form -> 400px is single column, 520px (+) grid rendered
	
				document.getElementById("myForm").style.width = payfields["width"];
	
				const params = (new URL(location)).searchParams;
				
				switch(params.get('option')) {
					case '1':
	
						/** use this form to create a payment intent and tokenize the account to be used in your own payment attempt ***/
	
						payfields["timestamp"] = Math.floor(Date.now() / 1000); // Current Unix timestamp -> used for auto-reload
						payfields["payment-intent"] = 1; 
						payfields["domain"] = host_domain; // Domain of the website that will host this embeddedpayment form
						payfields["app-key"] = qor_app_key;
						payfields["client-key"] = qor_client_key;
						payfields["mid"] = qor_mid;
						payfields["include-cardholder"] = 1; // turn cardholder field on / off
						payfields["include-street"] = 0;  // turn steet field on / off
						payfields["include-zip"] = 1; // turn zip field on / off -> used for AVS
						payfields["button-submit"] = 0;
						payfields["button-text"] = "Place Order (intent)"; 
						payfields["include-store-card"] = 1;// set the submit button text
						//payfields["profile-id"] = '12345'; // tis is yor profile / customer id that we can assign to the token
	
						break;
	
					case '2':
	
						/** -- OR -- **/
						/** use this form to create a payment token to be used in your own payment attempt or simply save a payment method and store that token for your customer ***/
						
						payfields["timestamp"] = Math.floor(Date.now() / 1000); // Current Unix timestamp -> used for auto-reload
						payfields["payment-token"] = 1; 
						payfields["domain"] = host_domain; // Domain of the website that will host this embeddedpayment form
						payfields["app-key"] = qor_app_key;
						payfields["client-key"] = qor_client_key;
						payfields["mid"] = qor_mid;
						payfields["include-cardholder"] = 1; // turn cardholder field on / off
						payfields["include-street"] = 0;  // turn steet field on / off
						payfields["include-zip"] = 1; // turn zip field on / off -> used for AVS
						payfields["button-submit"] = 0;
						payfields["button-text"] = "Create Payment Token";
						payfields["include-store-card"] = 1; // set the submit button text
						//payfields["profile-id"] = '12345'; // tis is yor profile / customer id that we can assign to the token
	
						break;
	
					case '3':
	
						/** -- OR -- **/
						/*** send a form ID for a saved form template built from the assorted option of attributes ***/
	
						// JSON of attributes saved in the form template (DB)
						/*
						{
							"payment-intent": 0,
							"app-key": "T6554252567241061980",
							"client-key": "01dffeb784c64d098c8c691ea589eb82",
							"domain": "https://secure.qorcommerce.io",
							"mid": "887728203",
							"use3DS": 0,
							"css-url": "https://secure.qorcommerce.io/css/standard.css",
							"include-cardholder": 1,
							"include-street": 0,
							"include-zip": 1,
							"button-submit": 1,
							"button-text": "Place Order",
							"auto-reload": 0,
							"store-card": 0,
							"include-store-card": 1,
							"store-card-text": "Store for later use",
							"required-fields": "cardholdername,account,expdate,cv,zip"
						}
						*/
	
						payfields["timestamp"] = Math.floor(Date.now() / 1000); // Current Unix timestamp -> used for auto-reload
						payfields["form_id"] = qor_form_id; 
						payfields["domain"] = host_domain; // id this is sent this overrides the saved host domain
						payfields["app-key"] = qor_app_key;
						payfields["client-key"] = qor_client_key;
						payfields["amount"] = '10.10'; // amount of order or shopping cart to be charged
						payfields["orderid"] = "oid-" + Math.floor(Math.random() * 100000) + 1000;
						payfields["include-store-card"] = 1; // unique order id - REPLACE with our oredr id
						//payfields["profile-id"] = '12345'; // tis is yor profile / customer id that we can assign to the order
						//payfields["exp-show-month"] = 0; // show 3 character month helper in expiration month select box
	
						break;
	
					case '4':
	
						/** -- OR -- **/
						/*** send everything to build an embedded form ***/
					
						payfields["timestamp"] = Math.floor(Date.now() / 1000); // Current Unix timestamp -> used for auto-reload
						payfields["domain"] = host_domain; // Domain of the website that will host this embeddedpayment form
						payfields["app-key"] = qor_app_key;
						payfields["client-key"] = qor_client_key;
						payfields["mid"] = qor_mid;
						//payfields["profile-id"] = '12345'; // tis is yor profile / customer id that we can assign to the order
	
						//$payfields["background-color"] = "#e7f0ff"; // a color eg. red -or- the hex eg. #e7f0ff
	
						payfields["amount"] = '10.10'; // amount of order or shopping cart to be charged
						payfields["orderid"] = "oid-" + Math.floor(Math.random() * 100000) + 1000;; // unique order id - REPLACE with our oredr id
	
						//payfields["account"] = "131942$U60MLwx2"; // send a tokenized account to be charged (???? needs to be encrypted)
	
						payfields["use3DS"] = 0; // turn on 3D secure usage
	
						payfields["css-url"] = host_domain + "/css/standard.css";
						//payfields["css-url"] = host_domain + "/css/modern.css";
						//payfields["css-url"] = host_domain + "/css/floating.css"; // not done yet
	
						//payfields["expdate-format"] = "separate"; // will also render exp month/year MM/YYYY - separate is default
						//payfields["expdate-format"] = "merged"; // will also render exp month/year MM/YYYY - separate is default
						payfields["include-cardholder"] = 1; // turn cardholder field on / off
						//payfields["include-street"] = 1;  // turn steet field on / off
						payfields["include-zip"] = 1;
						payfields["include-store-card"] = 1; // turn zip field on / off -> used for AVS
	
						//payfields["auto-reload"] = 1; // not done yet -> used for AVS
	
						payfields["store-card"] = 3; // store card for later use (manual push) - if this is set then include-store-card is by passed
						//payfields["include-store-card"] = 1; // show "Store for later use" checkbox
						//payfields["store-card-text"] = "Store for later use"; // set the store card text on the form
	
						payfields["button-submit"] = 0;
						payfields["button-text"] = "Place Order"; // set the submit button text
	
						break;
	
					case '5':
	
						/*** Use the modern CSS example **/
						/*** send a form ID for a saved form template built from the assorted option of attributes ***/					
	
						payfields["timestamp"] = Math.floor(Date.now() / 1000); // Current Unix timestamp -> used for auto-reload
						payfields["form_id"] = qor_form_id2; 
						payfields["css-url"] = host_domain + "/css/modern.css";
						payfields["domain"] = host_domain; // id this is sent this overrides the saved host domain
						payfields["app-key"] = qor_app_key;
						payfields["client-key"] = qor_client_key;
						payfields["amount"] = "10.10"; // amount of order or shopping cart to be charged
						payfields["orderid"] = "oid-" + Math.floor(Math.random() * 100000) + 1000;; // unique order id - REPLACE with our oredr id
						payfields["include-store-card"] = 1;
						break;
	
					default:
	
						payfields["timestamp"] = Math.floor(Date.now() / 1000); // Current Unix timestamp -> used for auto-reload
						payfields["form_id"] = qor_form_id; 
						payfields["domain"] = host_domain; // id this is sent this overrides the saved host domain
						payfields["app-key"] = qor_app_key;
						payfields["client-key"] = qor_client_key;
						payfields["cardholder-text"] = "Jonathan Pittman";
						payfields["zip-text"] = "32606";
						payfields["amount"] = '10.10'; // amount of order or shopping cart to be charged
						payfields["orderid"] = "oid-" + Math.floor(Math.random() * 100000) + 1000;; // unique order id - REPLACE with our oredr id
						payfields["include-store-card"] = 1;
				}
	 
				let data_to_hash = Object.values(payfields).join(''); // implode values only
	
				// hash and build hmac
				HMAC(qor_client_key, data_to_hash)
					 .then(function(hash) { 
						 document.getElementById("pay-form").setAttribute("data-hmac-hmacsha256", hash); 
	
						 for (const [key, value] of Object.entries(payfields)) {
							 document.getElementById("pay-form").setAttribute("data-hmac-"+key, value); 
						}
	
						 var paymentForm = new QorPaymentForm( "pay-form","https://secure.qorcommerce.io");
	
						/***
							* define your own submit button 
							* When generating the iframe, send the attribute button-submit = 0.
								This will hide the payment forms default submit button.
							• to trigger a submit of the payment form, use the paymentForm object's
								submitPayment method
						***/
					 
						var mySubmitButton = document.getElementById("my-submit-button");
						mySubmitButton.addEventListener("click", function() {
								paymentForm.submitPayment();
                                 

                                  paymentForm.onSubmit(function(response) {
                            
							if (response.code === 'approved') {
                                
								// alert( "Received an approval: " + response.onSuccess );
								// record the transaction, save the token if needed and finish order/sale
                                var TokenID = JSON.parse(response.onSuccess);
																	console.log(response.onSuccess)
																console.log(TokenID.token);
								var GHJ = TokenID.token
                         	localStorage.setItem('TokenEatz', GHJ)
               var btnsaver	= document.getElementById('datasaver')
								btnsaver.click();
										
												  
													// this.apiostdemo()

                // document.getElementById('tokenarryhere').innerText= GHJ;


                        

                                //         var tokendot  = TokenID.token;
                              

                                		 
                                 
                                								// setTimeout(window.location.assign("http://127.0.0.1:8000/thank-you.html"), 5000);

								window.location.assign("thank-you");
                                    // document.getElementById('tokenarryhere').innerText = response.onSuccess;
                                 
                                

							} 
                            else {
								// alert( "Received a decline: " + response.onError );
					
								console.log(response.onError);
								// paymentForm.enableSubmitButton();
							}
						});
 
							});
					 
	
						
	
						paymentForm.request();
					 } );
    },
  },
};
</script>

<style>
div#myForm {
    width: 100% !important;
}
iframe {
	width: 100%;
	border: none;
	margin-top: 1rem;
	padding: 0;
	height: 342px;
}
button {
	padding: .75rem 1rem;
	font-size: 1rem;
	background-color: #DDD;
	border: none;
	cursor: pointer;
}
 .showingdetails {
    font-weight: 700;
    background: black;
    color: white;
    padding: 8px;
		cursor: pointer;
    border-radius: 4px;
		    width: 50%;
}
.showingdetails:hover{
	background: goldenrod;
}
iframe#pay-formx {
    height: 31% !important;
}
 
button#payment-submit-button {
    display: none !important;
}
form#order-form {
    margin-bottom: 20% !important;
}
iframe#pay-formx {
    height: 31% !important;
}
 
button#payment-submit-button {
    display: none !important;
}
form#order-form {
    margin-bottom: 20% !important;
}
iframe#pay-formx {
    height: 31% !important;
}
 
button#payment-submit-button {
    display: none !important;
}
form#order-form {
    margin-bottom: 20% !important;
}
</style>