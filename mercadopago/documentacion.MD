Mercado Pago API
La API de Mercado Pago te permite interactuar de forma directa con toda la información que necesitas para desarrollar tus integraciones como quieras.

Para realizar solicitudes, tienes que autenticar cada pedido con las credenciales de tu aplicación. Si aún no tienes una, consulta los pasos básicos para comenzar a implementar nuestras soluciones.

Base URL
https://api.mercadopago.com

Autenticación
Para conectar las integraciones y tu cuenta de una manera segura, tienes que realizar todas las solicitudes a nuestra API utilizando HTTPS e incluir las credenciales de tu aplicación para autenticar cada interacción.
Las llamadas realizadas a través de HTTP simple y sin autenticación, fallarán.

Importante
Nunca expongas tu clave privada en un repositorio público, ni la utilices en tu código client-side. Siempre hazlo de manera segura desde tu servidor.
Configura el Access Token en el header Authorization de todas las solicitudes como muestra el siguiente ejemplo.

cURL ejemplo

curl -H 'Authorization: Bearer <ENV_ACCESS_TOKEN>'  \
                    https://api.mercadopago.com/V1/payments

----

Checkout API (Orders)
Mercado Pago's Transparent Checkout (or Checkout API) allows the entire checkout process, from filling in user data to making the payment, to take place in a single environment, without the need to redirect to a page outside your store.

For a full documentation, refer to our Developer's Site.

Make sure to:

Read the testing documentation

Create an account and get the testing credentials

Select the Environment "Integration"

Set the "public_key" and "access_token" variables with the test credentials

Check the availability of Orders API in your country of operation here.

You may to need to set collection variables as you go to test all resources (payment id, customer id, card id, etc.)

Payment Methods
Payment Methods API's gives information about the configuration for a certain card BIN. You can find information about the type of card, issuing bank and installments configured for a particular BIN.

GET
Get Payment Method
https://api.mercadopago.com/v1/payment_methods/search?public_key=•••••••&bins=&marketplace=NONE&id=
Consult the available payment methods for a certain BIN and obtain a list with the details of each one and its properties.

HEADERS
Content-Type
application/json

PARAMS
public_key
•••••••

(Required) Public key of the application

bins
(Optional) Bin of the card to search

marketplace
NONE

(Required) Must always be NONE

id
(Optional) ID of the payment method to search

Example Request
Get Payment Method
View More
curl
curl --location 'https://api.mercadopago.com/v1/payment_methods/search?public_key=%E2%80%A2%E2%80%A2%E2%80%A2%E2%80%A2%E2%80%A2%E2%80%A2%E2%80%A2&marketplace=NONE&bins=503175' \
--header 'Content-Type: application/json'
200 OK
Example Response
Body
Headers (1)
View More
json
{
  "paging": {
    "total": 1,
    "limit": 30,
    "offset": 0
  },
  "results": [
    {
      "financial_institutions": [],
      "secure_thumbnail": "https://http2.mlstatic.com/storage/logos-api-admin/0daa1670-5c81-11ec-ae75-df2bef173be2-xl@2x.png",
      "payer_costs": [
        {
          "installment_rate": 0,
          "discount_rate": 0,
          "min_allowed_amount": 3,
          "labels": [
            "CFT_0,00%|TEA_0,00%"
          ],
          "installments": 1,
          "reimbursement_rate": null,
          "max_allowed_amount": 5000000,
          "payment_method_option_id": "1.AQokODllZjQyNjktYjAzMy00OWU1LWJhMWQtNDE0NjQyNTM3MzY4EJaFuevHLg"
        },
        {
          "installment_rate": 0,
          "discount_rate": 0,
          "min_allowed_amount": 3,
          "labels": [
            "interest_deduction_by_collector",
            "CFT_0,00%|TEA_0,00%"
          ],
          "installments": 2,
          "reimbursement_rate": 0,
          "max_allowed_amount": 5000000,
          "payment_method_option_id": "1.AQokODllZjQyNjktYjAzMy00OWU1LWJhMWQtNDE0NjQyNTM3MzY4EJaFuevHLg"
        },
        {
          "installment_rate": 0,
          "discount_rate": 0,
          "min_allowed_amount": 3,
          "labels": [
            "interest_deduction_by_collector",
            "CFT_0,00%|TEA_0,00%"
          ],
          "installments": 3,
          "reimbursement_rate": null,
          "max_allowed_amount": 5000000,
          "payment_method_option_id": "1.AQokODllZjQyNjktYjAzMy00OWU1LWJhMWQtNDE0NjQyNTM3MzY4EJaFuevHLg"
        },
        {
          "installment_rate": 0,
          "discount_rate": 0,
          "min_allowed_amount": 3,
          "labels": [
            "recommended_installment",
            "interest_deduction_by_collector",
            "CFT_0,00%|TEA_0,00%"
          ],
          "installments": 6,
          "reimbursement_rate": null,
          "max_allowed_amount": 5000000,
          "payment_method_option_id": "1.AQokODllZjQyNjktYjAzMy00OWU1LWJhMWQtNDE0NjQyNTM3MzY4EJaFuevHLg"
        },
        {
          "installment_rate": 84.29,
          "discount_rate": 0,
          "min_allowed_amount": 3,
          "labels": [
            "CFT_400,00%|TEA_295,00%"
          ],
          "installments": 9,
          "reimbursement_rate": null,
          "max_allowed_amount": 5000000,
          "payment_method_option_id": "1.AQokODllZjQyNjktYjAzMy00OWU1LWJhMWQtNDE0NjQyNTM3MzY4EJaFuevHLg"
        },
        {
          "installment_rate": 113.5,
          "discount_rate": 0,
          "min_allowed_amount": 3,
          "labels": [
            "CFT_390,00%|TEA_290,00%"
          ],
          "installments": 12,
          "reimbursement_rate": null,
          "max_allowed_amount": 5000000,
          "payment_method_option_id": "1.AQokODllZjQyNjktYjAzMy00OWU1LWJhMWQtNDE0NjQyNTM3MzY4EJaFuevHLg"
        },
        {
          "installment_rate": 180.78,
          "discount_rate": 0,
          "min_allowed_amount": 3,
          "labels": [
            "CFT_390,00%|TEA_293,00%"
          ],
          "installments": 18,
          "reimbursement_rate": null,
          "max_allowed_amount": 5000000,
          "payment_method_option_id": "1.AQokODllZjQyNjktYjAzMy00OWU1LWJhMWQtNDE0NjQyNTM3MzY4EJaFuevHLg"
        },
        {
          "installment_rate": 254.62,
          "discount_rate": 0,
          "min_allowed_amount": 3,
          "labels": [
            "CFT_390,00%|TEA_294,00%"
          ],
          "installments": 24,
          "reimbursement_rate": null,
          "max_allowed_amount": 5000000,
          "payment_method_option_id": "1.AQokODllZjQyNjktYjAzMy00OWU1LWJhMWQtNDE0NjQyNTM3MzY4EJaFuevHLg"
        }
      ],
      "issuer": {
        "default": true,
        "name": "Mastercard",
        "id": 3
      },
      "total_financial_cost": 0,
      "min_accreditation_days": 0,
      "max_accreditation_days": 2,
      "merchant_account_id": null,
      "id": "master",
      "payment_type_id": "credit_card",
      "accreditation_time": 2880,
      "settings": [
        {
          "security_code": {
            "mode": "mandatory",
            "card_location": "back",
            "length": 3
          },
          "card_number": {
            "length": 16,
            "validation": "standard"
          },
          "bin": {
            "pattern": "^(5|(2(221|222|223|224|225|226|227|228|229|23|24|25|26|27|28|29|3|4|5|6|70|71|720)))",
            "installments_pattern": "^(?!(554730|525855|547883|553461|540573|539522|539500|525562|539520|539508|539481|539479|515771|521219|521246|223143|223046|223226|223236|223269|234051|511657|519168|520812|522513|523793|523863|524728|526773|528104|528433|530815|530877|531929|533305|533324|533331|534090|536523|537012|540615|541097|542744|544512|544683|551743|555264|555755|555840|555848|558777|559137|230570|230709|230724|230895|230933|230937|511658|512258|512834|516656|519020|519879|522428|522713|525337|530516|531984|537067|538172|542734|542755|547320|549807|550480|552999|554630|559219|501092|528824))",
            "exclusion_pattern": "^(555889|504639|504570|542878|532383|515070|515073|560718|551314|526497|524313|559926|559109|559100|557917|551200|541409|539110|536671|536670|536560|533888|533871|533860|533423|531179|531141|530779|522128|518787|515845|505865|505864|505863|232004|557069|555902|536196|532309|531441|530815|522684|501108|501107|501104|230867|230688|593628|592501|593626|514256|514586|526461|511309|514285|501059|557909|589633|553839|553777|553771|551792|528733|549180|528745|517562|511849|557648|546367|501070|601782|508143|501085|501074|501073|501071|501068|501066|589671|588729|501089|501083|501082|501081|501080|501075|501067|501062|501061|501060|501058|501057|501056|501055|501054|501053|501051|501049|501047|501045|501043|501041|501040|501039|501038|501029|501028|501027|501026|501025|501024|501023|501021|501020|501018|501016|501015|589657|589562|501105|557039|562397|566694|566783|568382|569322|504363|504338|504777|511673|514365|534935|222980|504520|544069|527558|511657|535456|535584|535585|250058|547526|514758|511080|514908|525559|542405|553474|553525|554763|557575|558418|558495|559442|527571|544768|504656|501063|504780|527341|511913|588800|546308|22345147)"
          }
        }
      ],
      "thumbnail": "https://http2.mlstatic.com/storage/logos-api-admin/0daa1670-5c81-11ec-ae75-df2bef173be2-xl@2x.png",
      "bins": [],
      "marketplace": "NONE",
      "deferred_capture": "supported",
      "agreements": [],
      "labels": [
        "recommended_method",
        "zero_dollar_auth"
      ],
      "financing_deals": {
        "legals": null,
        "installments": null,
        "expiration_date": null,
        "start_date": null,
        "status": "deactive"
      },
      "name": "Mastercard",
      "site_id": "MLA",
      "processing_mode": "aggregator",
      "additional_info_needed": [
        "cardholder_name",
        "cardholder_identification_type",
        "cardholder_identification_number",
        "issuer_id"
      ],
      "status": "active"
    }
  ]
}
GET
Get Installments
https://api.mercadopago.com/v1/payment_methods/installments?public_key=•••••••&bin=&amount=&payment_method_id=
Consult the available installments for a certain BIN and amount combination.

HEADERS
Content-Type
application/json

PARAMS
public_key
•••••••

(Required) Public key of the appliaction

bin
(Optional) Bin of the card to search

amount
(Required) Amount of the sale

payment_method_id
(Required) ID of the payment method

Example Request
Get Installments
View More
curl
curl --location 'https://api.mercadopago.com/v1/payment_methods/installments?public_key=%E2%80%A2%E2%80%A2%E2%80%A2%E2%80%A2%E2%80%A2%E2%80%A2%E2%80%A2&bin=503175&amount=20000' \
--header 'Content-Type: application/json'
200 OK
Example Response
Body
Headers (1)
View More
json
[
  {
    "payment_method_id": "master",
    "payment_type_id": "credit_card",
    "issuer": {
      "id": "3",
      "name": "Mastercard",
      "secure_thumbnail": "https://http2.mlstatic.com/storage/logos-api-admin/0daa1670-5c81-11ec-ae75-df2bef173be2-xl@2x.png",
      "thumbnail": "https://http2.mlstatic.com/storage/logos-api-admin/0daa1670-5c81-11ec-ae75-df2bef173be2-xl@2x.png"
    },
    "processing_mode": "aggregator",
    "merchant_account_id": null,
    "payer_costs": [
      {
        "installments": 1,
        "installment_rate": 0,
        "discount_rate": 0,
        "reimbursement_rate": null,
        "labels": [
          "CFT_0,00%|TEA_0,00%"
        ],
        "installment_rate_collector": [
          "MERCADOPAGO"
        ],
        "min_allowed_amount": 3,
        "max_allowed_amount": 5000000,
        "recommended_message": "1 cuota de $ 20.000,00 ($ 20.000,00)",
        "installment_amount": 20000,
        "total_amount": 20000,
        "payment_method_option_id": "1.AQokODllZjQyNjktYjAzMy00OWU1LWJhMWQtNDE0NjQyNTM3MzY4EJaFuevHLg"
      },
      {
        "installments": 2,
        "installment_rate": 0,
        "discount_rate": 0,
        "reimbursement_rate": 0,
        "labels": [
          "interest_deduction_by_collector",
          "CFT_0,00%|TEA_0,00%"
        ],
        "installment_rate_collector": [
          "MERCADOPAGO"
        ],
        "min_allowed_amount": 3,
        "max_allowed_amount": 5000000,
        "recommended_message": "2 cuotas de $ 10.000,00 ($ 20.000,00)",
        "installment_amount": 10000,
        "total_amount": 20000,
        "payment_method_option_id": "1.AQokODllZjQyNjktYjAzMy00OWU1LWJhMWQtNDE0NjQyNTM3MzY4EJaFuevHLg"
      },
      {
        "installments": 3,
        "installment_rate": 0,
        "discount_rate": 0,
        "reimbursement_rate": null,
        "labels": [
          "recommended_installment",
          "interest_deduction_by_collector",
          "CFT_0,00%|TEA_0,00%"
        ],
        "installment_rate_collector": [
          "MERCADOPAGO"
        ],
        "min_allowed_amount": 3,
        "max_allowed_amount": 5000000,
        "recommended_message": "3 cuotas de $ 6.666,67 ($ 20.000,00)",
        "installment_amount": 6666.67,
        "total_amount": 20000,
        "payment_method_option_id": "1.AQokODllZjQyNjktYjAzMy00OWU1LWJhMWQtNDE0NjQyNTM3MzY4EJaFuevHLg"
      },
      {
        "installments": 6,
        "installment_rate": 58.92,
        "discount_rate": 0,
        "reimbursement_rate": null,
        "labels": [
          "CFT_440,00%|TEA_318,00%",
          "recommended_interest_installment_with_some_banks"
        ],
        "installment_rate_collector": [
          "MERCADOPAGO"
        ],
        "min_allowed_amount": 3,
        "max_allowed_amount": 5000000,
        "recommended_message": "6 cuotas de $ 5.297,33 ($ 31.784,00)",
        "installment_amount": 5297.33,
        "total_amount": 31784,
        "payment_method_option_id": "1.AQokODllZjQyNjktYjAzMy00OWU1LWJhMWQtNDE0NjQyNTM3MzY4EJaFuevHLg"
      },
      {
        "installments": 9,
        "installment_rate": 84.29,
        "discount_rate": 0,
        "reimbursement_rate": null,
        "labels": [
          "CFT_400,00%|TEA_295,00%"
        ],
        "installment_rate_collector": [
          "MERCADOPAGO"
        ],
        "min_allowed_amount": 3,
        "max_allowed_amount": 5000000,
        "recommended_message": "9 cuotas de $ 4.095,33 ($ 36.858,00)",
        "installment_amount": 4095.33,
        "total_amount": 36858,
        "payment_method_option_id": "1.AQokODllZjQyNjktYjAzMy00OWU1LWJhMWQtNDE0NjQyNTM3MzY4EJaFuevHLg"
      },
      {
        "installments": 12,
        "installment_rate": 113.5,
        "discount_rate": 0,
        "reimbursement_rate": null,
        "labels": [
          "CFT_390,00%|TEA_290,00%"
        ],
        "installment_rate_collector": [
          "MERCADOPAGO"
        ],
        "min_allowed_amount": 3,
        "max_allowed_amount": 5000000,
        "recommended_message": "12 cuotas de $ 3.558,33 ($ 42.700,00)",
        "installment_amount": 3558.33,
        "total_amount": 42700,
        "payment_method_option_id": "1.AQokODllZjQyNjktYjAzMy00OWU1LWJhMWQtNDE0NjQyNTM3MzY4EJaFuevHLg"
      },
      {
        "installments": 18,
        "installment_rate": 180.78,
        "discount_rate": 0,
        "reimbursement_rate": null,
        "labels": [
          "CFT_390,00%|TEA_293,00%"
        ],
        "installment_rate_collector": [
          "MERCADOPAGO"
        ],
        "min_allowed_amount": 3,
        "max_allowed_amount": 5000000,
        "recommended_message": "18 cuotas de $ 3.119,78 ($ 56.156,00)",
        "installment_amount": 3119.78,
        "total_amount": 56156,
        "payment_method_option_id": "1.AQokODllZjQyNjktYjAzMy00OWU1LWJhMWQtNDE0NjQyNTM3MzY4EJaFuevHLg"
      },
      {
        "installments": 24,
        "installment_rate": 254.62,
        "discount_rate": 0,
        "reimbursement_rate": null,
        "labels": [
          "CFT_390,00%|TEA_294,00%"
        ],
        "installment_rate_collector": [
          "MERCADOPAGO"
        ],
        "min_allowed_amount": 3,
        "max_allowed_amount": 5000000,
        "recommended_message": "24 cuotas de $ 2.955,17 ($ 70.924,00)",
        "installment_amount": 2955.17,
        "total_amount": 70924,
        "payment_method_option_id": "1.AQokODllZjQyNjktYjAzMy00OWU1LWJhMWQtNDE0NjQyNTM3MzY4EJaFuevHLg"
      }
    ],
    "agreements": null
  }
]
Identification Types
Get the available identification types supported for your site of operation and it's properties.

The site of operation is inferred from the credentials.

Orders
Create Orders with cards (credit, debit, prepaid), and other payment methods. There is multiple examples for different industries and categories. All the payer and Items information is used for fraud prevention reasons, make sure to send it as detailes as possible.

An Idempotency Key (sent as header) is required when creating or updating an Order. In this collection the header is automatically generated.

Create Order
This endpoint allows to create Orders in "automatic" (processing the transaction in a single stage) or "manual" (processing the transaction in stages that can be configured and executed incrementally) mode for payment transactions. In case of success, the request will return a response with status 201

POST
Create order - Manual mode
https://api.mercadopago.com/v1/orders
This endpoint allows you to create an Order in “manual” mode (processing the transaction in steps that can be configured and executed incrementally) for payment and cash-out transactions. If successful, the request will return a response with status 201.

AUTHORIZATION
Bearer Token
Token
•••••••

HEADERS
Content-Type
application/json

X-Idempotency-Key
Idempotency_Value

(Required) A unique key that the server uses to recognize subsequent retries of the same request.

Body
raw
View More
{
    "type": "online",
    "external_reference": "ext_ref_1234",
    "payer": {
        "email": "test@testuser.com",
        "entity_type": "individual",
        "first_name": "João",
        "last_name": "Silva",
        "identification": {
            "type": "CPF",
            "number": "12345678909"
        },
        "phone": {
            "area_code": "11",
            "number": "98765-4321"
        },
        "address": {
            "zip_code": "06233-903",
            "street_name": "Rua Teste",
            "street_number": "3003",
            "neighborhood": "Pres. Altino",
            "state": "SP",
            "city": "Osasco",
            "complement": "Apto 303"
        }
    },
    "shipment": {
        "address": {
            "zip_code": "06233-903",
            "street_name": "Rua Teste",
            "street_number": "3003",
            "neighborhood": "Pres. Altino",
            "city": "Osasco",
            "state": "SP",
            "complement": "Apto 303"
        }
    },
    "total_amount": "12.90",
    "capture_mode": "automatic",
    "processing_mode": "manual",
    "description": "Smartphone",
    "integration_data": {
        "integrator_id": "dev_1234567890", //For Certified Developers
        "platform_id": "1234567890"
    },
    "items": [
        {
            "title": "Smartphone",
            "unit_price": "12.90",
            "quantity": 1,
            "description": "Smartphone",
            "external_code": "1234",
            "picture_url": "https://http2.mlstatic.com/resources/frontend/statics/growth-sellers-landings/device-mlb-point-i_medium2x.png",
            "category_id": "MLB1055",
            "type": "MLB1055",
            "warranty": true
        }
    ]
}
Example Request
Create order - Manual mode
View More
curl
curl --location 'https://api.mercadopago.com/v1/orders' \
--header 'Content-Type: application/json' \
--header 'X-Idempotency-Key: Idempotency_Value' \
--data-raw '{
    "type": "online",
    "external_reference": "ext_ref_1234",
    "payer": {
        "email": "test@testuser.com",
        "entity_type": "individual",
        "first_name": "João",
        "last_name": "Silva",
        "identification": {
            "type": "CPF",
            "number": "12345678909"
        },
        "phone": {
            "area_code": "11",
            "number": "98765-4321"
        },
        "address": {
            "zip_code": "06233-903",
            "street_name": "Rua Teste",
            "street_number": "3003",
            "neighborhood": "Pres. Altino",
            "state": "SP",
            "city": "Osasco",
            "complement": "Apto 303"
        }
    },
    "shipment": {
        "address": {
            "zip_code": "06233-903",
            "street_name": "Rua Teste",
            "street_number": "3003",
            "neighborhood": "Pres. Altino",
            "city": "Osasco",
            "state": "SP",
            "complement": "Apto 303"
        }
    },
    "total_amount": "12.90",
    "capture_mode": "automatic",
    "processing_mode": "manual",
    "description": "Smartphone",
    "integration_data": {
        "integrator_id": "dev_1234567890", //For Certified Developers
        "platform_id": "1234567890"
    },
    "items": [
        {
            "title": "Smartphone",
            "unit_price": "12.90",
            "quantity": 1,
            "description": "Smartphone",
            "external_code": "1234",
            "picture_url": "http2.mlstatic.com/resources/frontend/statics/growth-sellers-landings/device-mlb-point-i_medium2x.png",
            "category_id": "MLB1055",
            "type": "MLB1055",
            "warranty": true
        }
    ]
}'
201 Created
Example Response
Body
Headers (1)
View More
json
{
  "id": "ORDTST01KFBW5NDNSXYXETNXHEB1MR4T",
  "type": "online",
  "processing_mode": "manual",
  "external_reference": "ext_ref_1234",
  "description": "Smartphone",
  "total_amount": "12.90",
  "country_code": "BRA",
  "user_id": "123456789",
  "status": "created",
  "capture_mode": "automatic",
  "client_token": "eyJ*********oNg",
  "currency": "BRL",
  "created_date": "2026-01-19T19:37:08.789Z",
  "last_updated_date": "2026-01-19T19:37:08.789Z",
  "integration_data": {
    "application_id": "123456789123456789",
    "integrator_id": "dev_1234567890",
    "platform_id": "1234567890"
  },
  "shipment": {
    "address": {
      "street_name": "Rua Teste",
      "street_number": "3003",
      "zip_code": "06233-903",
      "neighborhood": "Pres. Altino",
      "city": "Osasco",
      "state": "SP",
      "complement": "Apto 303"
    }
  },
  "items": [
    {
      "category_id": "MLB1055",
      "type": "MLB1055",
      "title": "Smartphone",
      "description": "Smartphone",
      "unit_price": "12.90",
      "picture_url": "https://http2.mlstatic.com/resources/frontend/statics/growth-sellers-landings/device-mlb-point-i_medium2x.png",
      "external_code": "1234",
      "quantity": 1
    }
  ]
}
POST
Create order - Tourism
https://api.mercadopago.com/v1/orders
Example of order containing all the required information for Tourism industry. The information is required for payment fraud purposes.

AUTHORIZATION
Bearer Token
Token
•••••••

HEADERS
Content-Type
application/json

X-Idempotency-Key
Idempotency_Value

(Required) A unique key that the server uses to recognize subsequent retries of the same request.

Body
raw
View More
{
  "type": "online",
  "total_amount": "200.00",
  "processing_mode": "automatic",
  "external_reference": "ext_ref_1234",
  "payer": {
    "email": "test@testuser.com",
    "identification": {
      "type": "CPF",
      "number": "12345678909"
    },
    "first_name": "Joao",
    "last_name": "Silva",
    "phone": {
      "area_code": "11",
      "number": "987654321"
    },
    "address": {
      "zip_code": "11034430",
      "street_name": "Av. Paulista",
      "street_number": "100",
      "city": "SAO PAULO",
      "state": "SP",
      "neighborhood": "BELA VISTA",
      "complement": "101"
    }
  },
  "transactions": {
    "payments": [
      {
        "amount": "200.00",
        "expiration_time": "P1D",
        "payment_method": {
          "id": "pix",
          "type": "bank_transfer"
        }
      }
    ]
  },
  "items": [
    {
      "title": "Passagem Aérea",
      "description": "Passagem aérea São Paulo - Rio de Janeiro",
      "picture_url": "https://example.com/image.jpg",
      "category_id": "travel",
      "quantity": 1,
      "unit_price": "100.00"
    },
    {
      "title": "Passagem Aérea",
      "description": "Passagem aérea Rio de Janeiro - São Paulo",
      "picture_url": "https://example.com/image.jpg",
      "category_id": "travel",
      "quantity": 1,
      "unit_price": "100.00"
    }
  ],
  "additional_info": {
    "payer.registration_date": "2020-01-15T10:30:00Z",
    "payer.authentication_type": "MOBILE",
    "payer.is_prime_user": true,
    "payer.is_first_purchase_online": false,
    "payer.last_purchase": "2024-01-10T15:45:00Z",
    "travel.passengers": [
      {
        "first_name": "João",
        "last_name": "Silva",
        "identification": {
          "number": "12345678901",
          "type": "CPF"
        }
      },
      {
        "first_name": "Maria",
        "last_name": "Santos",
        "identification": {
          "number": "98765432109",
          "type": "CPF"
        }
      }
    ],
    "travel.routes": [
      {
        "departure": "GRU",
        "destination": "SDU",
        "departure_date_time": "2024-12-25T08:00:00Z",
        "arrival_date_time": "2024-12-25T09:30:00Z",
        "company": "LATAM"
      },
      {
        "departure": "SDU",
        "destination": "GRU",
        "departure_date_time": "2024-12-30T18:00:00Z",
        "arrival_date_time": "2024-12-30T19:30:00Z",
        "company": "LATAM"
      }
    ]
  }
}
  
Example Request
Create order - Tourism
View More
curl
curl --location 'https://api.mercadopago.com/v1/orders' \
--header 'Content-Type: application/json' \
--header 'X-Idempotency-Key: Idempotency_Value' \
--data-raw '{
  "type": "online",
  "total_amount": "200.00",
  "processing_mode": "automatic",
  "external_reference": "ext_ref_1234",
  "payer": {
    "email": "test@testuser.com",
    "identification": {
      "type": "CPF",
      "number": "12345678909"
    },
    "first_name": "Joao",
    "last_name": "Silva",
    "phone": {
      "area_code": "11",
      "number": "987654321"
    },
    "address": {
      "zip_code": "11034430",
      "street_name": "Av. Paulista",
      "street_number": "100",
      "city": "SAO PAULO",
      "state": "SP",
      "neighborhood": "BELA VISTA",
      "complement": "101"
    }
  },
  "transactions": {
    "payments": [
      {
        "amount": "200.00",
        "expiration_time": "P1D",
        "payment_method": {
          "id": "pix",
          "type": "bank_transfer"
        }
      }
    ]
  },
  "items": [
    {
      "title": "Passagem Aérea",
      "description": "Passagem aérea São Paulo - Rio de Janeiro",
      "picture_url": "https://example.com/image.jpg",
      "category_id": "travel",
      "quantity": 1,
      "unit_price": "100.00"
    },
    {
      "title": "Passagem Aérea",
      "description": "Passagem aérea Rio de Janeiro - São Paulo",
      "picture_url": "https://example.com/image.jpg",
      "category_id": "travel",
      "quantity": 1,
      "unit_price": "100.00"
    }
  ],
  "additional_info": {
    "payer.registration_date": "2020-01-15T10:30:00Z",
    "payer.authentication_type": "MOBILE",
    "payer.is_prime_user": true,
    "payer.is_first_purchase_online": false,
    "payer.last_purchase": "2024-01-10T15:45:00Z",
    "travel.passengers": [
      {
        "first_name": "João",
        "last_name": "Silva",
        "identification": {
          "number": "12345678901",
          "type": "CPF"
        }
      },
      {
        "first_name": "Maria",
        "last_name": "Santos",
        "identification": {
          "number": "98765432109",
          "type": "CPF"
        }
      }
    ],
    "travel.routes": [
      {
        "departure": "GRU",
        "destination": "SDU",
        "departure_date_time": "2024-12-25T08:00:00Z",
        "arrival_date_time": "2024-12-25T09:30:00Z",
        "company": "LATAM"
      },
      {
        "departure": "SDU",
        "destination": "GRU",
        "departure_date_time": "2024-12-30T18:00:00Z",
        "arrival_date_time": "2024-12-30T19:30:00Z",
        "company": "LATAM"
      }
    ]
  }
}
  '
201 Created
Example Response
Body
Headers (1)
View More
json
{
  "id": "ORDTST01KED2KSC1DB9HAWHVBSQPGAQQ",
  "type": "online",
  "processing_mode": "automatic",
  "external_reference": "ext_ref_1234",
  "total_amount": "200.00",
  "total_paid_amount": "200.00",
  "country_code": "BRA",
  "user_id": "123456789",
  "status": "action_required",
  "status_detail": "waiting_transfer",
  "capture_mode": "automatic_async",
  "currency": "BRL",
  "created_date": "2026-01-07T20:33:15.65Z",
  "last_updated_date": "2026-01-07T20:33:16.617Z",
  "integration_data": {
    "application_id": "123456789123456789"
  },
  "transactions": {
    "payments": [
      {
        "id": "PAY01KED2KSC1DB9HAWHVBV7GE1K7",
        "amount": "200.00",
        "expiration_time": "P1D",
        "date_of_expiration": "2026-01-08T20:33:15.706+00:00",
        "reference_id": "000884dkf2",
        "status": "action_required",
        "status_detail": "waiting_transfer",
        "payment_method": {
          "id": "pix",
          "type": "bank_transfer",
          "ticket_url": "https://www.mercadopago.com.br/sandbox/payments/140417280051/ticket?caller_id=123456789&hash=689babe4-4898-4b57-aee3-011111111",
          "qr_code": "000*************FF2",
          "qr_code_base64": "iVB***************ggg=="
        }
      }
    ]
  },
  "items": [
    {
      "category_id": "travel",
      "title": "Passagem Aérea",
      "description": "Passagem aérea São Paulo - Rio de Janeiro",
      "unit_price": "100.00",
      "picture_url": "https://example.com/image.jpg",
      "quantity": 1
    },
    {
      "category_id": "travel",
      "title": "Passagem Aérea",
      "description": "Passagem aérea Rio de Janeiro - São Paulo",
      "unit_price": "100.00",
      "picture_url": "https://example.com/image.jpg",
      "quantity": 1
    }
  ]
}
POST
Create order - Tickets & entertainment
https://api.mercadopago.com/v1/orders
Example of order containing all the required information for Tickets & Entertainment industry. The information is required for payment fraud purposes.

AUTHORIZATION
Bearer Token
Token
•••••••

HEADERS
Content-Type
application/json

X-Idempotency-Key
Idempotency_Value

(Required) A unique key that the server uses to recognize subsequent retries of the same request.

Body
raw
View More
{
  "type": "online",
  "total_amount": "100.00",
  "processing_mode": "automatic",
  "external_reference": "ext_ref_1234",
  "payer": {
    "email": "test@testuser.com",
    "identification": {
      "type": "CPF",
      "number": "12345678909"
    },
    "first_name": "Joao",
    "last_name": "Silva",
    "phone": {
      "area_code": "11",
      "number": "987654321"
    },
    "address": {
      "zip_code": "11034430",
      "street_name": "Av. Paulista",
      "street_number": "100",
      "city": "SAO PAULO",
      "state": "SP",
      "neighborhood": "BELA VISTA",
      "complement": "101"
    }
  },
  "transactions": {
    "payments": [
      {
        "amount": "100.00",
        "expiration_time": "P1D",
        "payment_method": {
          "id": "pix",
          "type": "bank_transfer"
        }
      }
    ]
  },
  "items": [
    {
      "title": "Ticket Plus",
      "description": "Ticket Plus - Concert",
      "picture_url": "https://example.com/image.jpg",
      "category_id": "Ticket",
      "quantity": 1,
      "unit_price": "100.00",
      "event_date": "2026-01-15T22:30:00Z"
    }
  ],
  "additional_info": {
    "payer.registration_date": "2020-01-15T10:30:00Z",
    "payer.authentication_type": "WEB",
    "payer.is_prime_user": true,
    "payer.is_first_purchase_online": false,
    "payer.last_purchase": "2024-01-10T15:45:00Z"
  }
}
  
Example Request
Create order - Tickets & entertainment
View More
curl
curl --location 'https://api.mercadopago.com/v1/orders' \
--header 'Content-Type: application/json' \
--header 'X-Idempotency-Key: Idempotency_Value' \
--data-raw '{
  "type": "online",
  "total_amount": "100.00",
  "processing_mode": "automatic",
  "external_reference": "ext_ref_1234",
  "payer": {
    "email": "test@testuser.com",
    "identification": {
      "type": "CPF",
      "number": "12345678909"
    },
    "first_name": "Joao",
    "last_name": "Silva",
    "phone": {
      "area_code": "11",
      "number": "987654321"
    },
    "address": {
      "zip_code": "11034430",
      "street_name": "Av. Paulista",
      "street_number": "100",
      "city": "SAO PAULO",
      "state": "SP",
      "neighborhood": "BELA VISTA",
      "complement": "101"
    }
  },
  "transactions": {
    "payments": [
      {
        "amount": "100.00",
        "expiration_time": "P1D",
        "payment_method": {
          "id": "pix",
          "type": "bank_transfer"
        }
      }
    ]
  },
  "items": [
    {
      "title": "Ticket Plus",
      "description": "Ticket Plus - Concert",
      "picture_url": "https://example.com/image.jpg",
      "category_id": "Ticket",
      "quantity": 1,
      "unit_price": "100.00",
      "event_date": "2026-01-15T22:30:00Z"
    }
  ],
  "additional_info": {
    "payer.registration_date": "2020-01-15T10:30:00Z",
    "payer.authentication_type": "WEB",
    "payer.is_prime_user": true,
    "payer.is_first_purchase_online": false,
    "payer.last_purchase": "2024-01-10T15:45:00Z"
  }
}
  '
201 Created
Example Response
Body
Headers (1)
View More
json
{
  "id": "ORDTST01KEHPCSQSSBSWXZGEC7HR43BH",
  "type": "online",
  "processing_mode": "automatic",
  "external_reference": "ext_ref_1234",
  "total_amount": "100.00",
  "total_paid_amount": "100.00",
  "country_code": "BRA",
  "user_id": "123456789",
  "status": "action_required",
  "status_detail": "waiting_transfer",
  "capture_mode": "automatic_async",
  "currency": "BRL",
  "created_date": "2026-01-09T15:35:55.897Z",
  "last_updated_date": "2026-01-09T15:35:56.876Z",
  "integration_data": {
    "application_id": "123456789123456789"
  },
  "transactions": {
    "payments": [
      {
        "id": "PAY01KEHPCSQSSBSWXZGEC92CGZZ2",
        "amount": "100.00",
        "expiration_time": "P1D",
        "date_of_expiration": "2026-01-10T15:35:55.946+00:00",
        "reference_id": "0008aaigzn",
        "status": "action_required",
        "status_detail": "waiting_transfer",
        "payment_method": {
          "id": "pix",
          "type": "bank_transfer",
          "ticket_url": "https://www.mercadopago.com.br/sandbox/payments/140673549423/ticket?caller_id=123456789&hash=bfcef0d5-4162-4668-b20d-011111111",
          "qr_code": "000*************A5E",
          "qr_code_base64": "iVB************Jggg=="
        }
      }
    ]
  },
  "items": [
    {
      "category_id": "Ticket",
      "title": "Ticket Plus",
      "description": "Ticket Plus - Concert",
      "unit_price": "100.00",
      "picture_url": "https://example.com/image.jpg",
      "quantity": 1
    }
  ]
}
Create Order (deferred capture)
Create an authorization for an Order and later capture the full amount or cancel the authorization.

Available for credit cards.

Availability of this feature may vary depending on operation site.

Order management
APIs used when processing_mode on the Order is set tu "manual".
Add, update or delete transactions. Process the orders once ready to have a final status on the transactions.

POST
Add transaction to order
https://api.mercadopago.com/v1/orders/:order_id/transactions
This endpoint allows you to add payment transactions to the order. This operation can only be performed in manual mode (processing the transaction in steps that can be configured and executed incrementally), with the processing_mode parameter set to manual. If successful, the request will return a response with status 201.

AUTHORIZATION
Bearer Token
Token
•••••••

HEADERS
Content-Type
application/json

X-Idempotency-Key
Idempotency_Value

(Required) A unique key that the server uses to recognize subsequent retries of the same request.

PATH VARIABLES
order_id
(Required) ID of the order.

Body
raw (json)
View More
json
{
    "payments": [
        {
            "amount": "24.90",
            "payment_method": {
                "id": "master",
                "type": "credit_card",
                "token": "{{card_token_id}}",
                "installments": 1,
                "statement_descriptor": "TestStore"
            }
        }
    ]
}
Example Request
Add transaction to order
View More
curl
curl --location 'https://api.mercadopago.com/v1/orders/:order_id/transactions' \
--header 'Content-Type: application/json' \
--header 'X-Idempotency-Key: Idempotency_Value' \
--data '{
    "payments": [
        {
            "amount": "24.90",
            "payment_method": {
                "id": "master",
                "type": "credit_card",
                "token": "{{card_token_id}}",
                "installments": 1,
                "statement_descriptor": "TestStore"
            }
        }
    ]
}'
201 Created
Example Response
Body
Headers (1)
View More
json
{
  "payments": [
    {
      "id": "PAY01KEHDFE1X5M838BVVKPRQ3SKJ",
      "amount": "24.90",
      "payment_method": {
        "id": "master",
        "type": "credit_card",
        "token": "339*****************1dd",
        "statement_descriptor": "TestStore",
        "installments": 1
      }
    }
  ]
}
Get order by ID
Get the current status of an Order and all it's related information with a query by Order ID

GET
Get order by ID
https://api.mercadopago.com/v1/orders/:order_id
This endpoint allows you to retrieve all information about an order using the ID obtained in the response to its creation. If successful, the request will return a response with status 200.

AUTHORIZATION
Bearer Token
Token
•••••••

HEADERS
Content-Type
application/json

PATH VARIABLES
order_id
(Required) ID of the order. Use variable to set orderId.

Example Request
Get order by ID
curl
curl --location 'https://api.mercadopago.com/v1/orders/:order_id' \
--header 'Content-Type: application/json'
200 OK
Example Response
Body
Headers (1)
View More
json
{
  "id": "ORDTST01KEHD4AJF0QRH2KR1X3Z4KDW1",
  "type": "online",
  "processing_mode": "manual",
  "external_reference": "ext_ref_1234",
  "description": "Smartphone",
  "total_amount": "12.90",
  "country_code": "BRA",
  "user_id": "123456789",
  "status": "created",
  "capture_mode": "automatic",
  "client_token": "eyJ*************kYQ",
  "currency": "BRL",
  "created_date": "2026-01-09T12:54:01.039Z",
  "last_updated_date": "2026-01-09T12:54:01.039Z",
  "integration_data": {
    "application_id": "123456789123456789",
    "integrator_id": "dev_1234567890",
    "platform_id": "1234567890"
  },
  "config": {
    "online": {
      "transaction_security": {
        "validation": "never"
      }
    }
  },
  "transactions": {
    "payments": [
      {
        "id": "PAY01KEHDA46H4YEBMT106ET0QH93",
        "amount": "24.90",
        "payment_method": {
          "id": "master",
          "type": "credit_card",
          "token": "c7f******************431",
          "statement_descriptor": "TestStore",
          "installments": 1
        }
      }
    ]
  },
  "items": [
    {
      "category_id": "MLB1055",
      "type": "MLB1055",
      "title": "Smartphone",
      "description": "Smartphone",
      "unit_price": "12.90",
      "picture_url": "https://http2.mlstatic.com/resources/frontend/statics/growth-sellers-landings/device-mlb-point-i_medium2x.png",
      "external_code": "1234",
      "quantity": 1
    }
  ]
}
Refunds
Make a complete or partial refund of an Order already processed.

Customers & Cards
Create Customers and associate Cards to them. All information will be safely stored in Mercado Pago's PCI vault.
With this integration you can show saved cards on your site for a better checkout experience.

Customers
A customer represents a user of your service / application.

It is mandatory to create a customer to add saved cards.

IMPORTANT: The customer is associated only to the application (account) that generated it, meaning it is encapsulated for a specific integration. It does not hold any ties to a customer of Mercado Pago or other integrations.

PUT
Update Customer
https://api.mercadopago.com/v1/customers/:customerId
Renew the data of a customer. Indicate the customer ID and send the parameters with the information you want to update.

AUTHORIZATION
Bearer Token
Token
•••••••

HEADERS
Content-Type
application/json

PATH VARIABLES
customerId
{{customer_id}}

(Required) ID of the customer

Body
raw (json)
View More
json
{
    "first_name": "New",
    "last_name": "Name",
    "phone": {
        "area_code": "27",
        "number": "999887766"
    },
    "identification": {
        "type": "DNI",
        "number": "12123124"
    },
    "date_registered": "2000-01-18",
    "description": "Description del user"
}
Example Request
Update Customer
View More
curl
curl --location --request PUT 'https://api.mercadopago.com/v1/customers/:customerId' \
--header 'Content-Type: application/json' \
--data '{
    "phone": {
        "area_code": "11",
        "number": "999887766"
    },
    "identification": {
        "type": "DNI",
        "number": "12123124"
    },
    "date_registered": "2000-01-18",
    "description": "Description del user"
}'
200 OK
Example Response
Body
Headers (1)
View More
json
{
  "id": "1670445614-6R4OLYVlnY2C00",
  "email": "test_payer_1234@testuser.com",
  "first_name": "Test",
  "last_name": "User",
  "phone": {
    "area_code": "11",
    "number": "999887766"
  },
  "identification": {
    "type": "DNI",
    "number": "12123124"
  },
  "address": {
    "id": "1355962743",
    "zip_code": "1406",
    "street_name": "Av. 9 de Julio",
    "street_number": 1500
  },
  "date_registered": "2000-01-18T00:00:00.000-04:00",
  "description": "Description del user",
  "date_created": "2024-02-05T15:46:44.840-04:00",
  "date_last_updated": "2024-02-05T15:50:24.359-04:00",
  "metadata": {
    "source_sync": "source_k"
  },
  "default_card": null,
  "default_address": "1355962743",
  "cards": [],
  "addresses": [
    {
      "apartment": null,
      "city": {
        "id": null,
        "name": "Buenos Aires"
      },
      "comments": null,
      "country": {
        "id": "AR",
        "name": "Argentina"
      },
      "date_created": "2024-02-05T15:46:44.785-04:00",
      "date_last_updated": null,
      "floor": null,
      "id": "1355962743",
      "municipality": {
        "id": null,
        "name": null
      },
      "name": null,
      "neighborhood": {
        "id": null,
        "name": null
      },
      "normalized": true,
      "phone": null,
      "state": {
        "id": "AR-C",
        "name": "Capital Federal"
      },
      "street_name": "Av. 9 de Julio",
      "street_number": 1500,
      "verifications": {
        "shipment": {
          "errors": [],
          "success": true
        }
      },
      "zip_code": "1406"
    }
  ],
  "live_mode": false,
  "user_id": 1670445614,
  "merchant_id": 1117105806,
  "client_id": 2826441017868072,
  "status": "active"
}
POST
Add Credit Card
https://api.mercadopago.com/v1/customers/:customerId/cards
Securely store on our servers the card reference used by the customer in the payment to avoid asking for all the data in future transactions.

AUTHORIZATION
Bearer Token
Token
•••••••

HEADERS
Content-Type
application/json

PATH VARIABLES
customerId
{{customer_id}}

(Required) Customer ID

Body
raw
{
    "token": "{{card_token_id}}",
    "payment_method_id": "visa"
}
Example Request
Add Credit Card
curl
curl --location 'https://api.mercadopago.com/v1/customers/:customerId/cards' \
--header 'Content-Type: application/json' \
--data '{
    "token": "{{card_token_id}}",
    "payment_method_id": "master"
}'
201 Created
Example Response
Body
Headers (1)
View More
json
{
  "additional_info": {
    "request_public": "true",
    "api_client_application": "traffic-layer",
    "api_client_scope": "mpapi-pci-tl"
  },
  "card_number_id": null,
  "cardholder": {
    "name": "APRO",
    "identification": {
      "number": "19119119100",
      "type": "DNI"
    }
  },
  "customer_id": "1670445614-6R4OLYVlnY2C00",
  "date_created": "2024-02-05T15:54:31.000-04:00",
  "date_last_updated": "2024-02-05T15:54:31.000-04:00",
  "expiration_month": 11,
  "expiration_year": 2025,
  "first_six_digits": "503175",
  "id": "9392738686",
  "issuer": {
    "id": 3,
    "name": "Mastercard"
  },
  "last_four_digits": "0604",
  "live_mode": true,
  "payment_method": {
    "id": "master",
    "name": "Mastercard",
    "payment_type_id": "credit_card",
    "thumbnail": "https://http2.mlstatic.com/storage/logos-api-admin/0daa1670-5c81-11ec-ae75-df2bef173be2-xl@2x.png",
    "secure_thumbnail": "https://http2.mlstatic.com/storage/logos-api-admin/0daa1670-5c81-11ec-ae75-df2bef173be2-xl@2x.png"
  },
  "security_code": {
    "length": 3,
    "card_location": "back"
  },
  "user_id": "1670445614"
}
----

Crear usuarios de prueba y responses:
Crear usuario de prueba
Este endpoint maneja las http requests para crear un usuario de prueba

POST

https://api.mercadopago.com/users/test
Copiar
Request parameters
Body

Obligatorio

Opcional
site_id
string
REQUERIDO

ID del sitio donde se creará el usuario de prueba.
MPE: Mercado Libre Perú
MLU: Mercado Libre Uruguay
MLA: Mercado Libre Argentina
Ver más

MLA
description
string
REQUERIDO

Descripción del usuario de pruebas que está siendo creado.
a description
Response parameters
id
number
ID único que identifica al usuario de prueba.
nickname
string
Apodo que identifica el usuario de prueba.
password
string
Contraseña de usuario de prueba.
site_status
string
Estado del sitio donde se creó el usuario de prueba.

Mostrar todo
Errors
400Bad request

400

Bad-Request

401Not found

401

Not-Found

403Forbidden

403

Forbidden

404Resource not found

404

Resource-Not-Found

500Internal server error

500

Internal-Server-Error


Consultar Errores
Request

cURL
Copiar
curl -X POST \
    'https://api.mercadopago.com/users/test'\
    -H 'Content-Type: application/json' \
       -H 'Authorization: Bearer APP_USR-4*********994754-12*********c73b4e1ac*********cef36b27c*********840' \
    -d '{
  "site_id": "MLA",
  "description": "a description"
}'
Response
Copiar
{
  "id": 123,
  "nickname": "TEST45I5GYIH",
  "password": "qatest6730",
  "site_status": "active",
  "site_id": "MLA",
  "description": "a description",
  "email": "test_user_123@testuser.com",
  "date_created": "2022-02-01T12:00:00.000-04:00",
  "date_last_updated": "2022-02-01T12:00:00.000-04:00"
}