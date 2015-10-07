# Smart Dealer Client API
API de comunicação (for PHP servers) com o software para revendas e concessionárias Smart Dealer

Para mais informações, acesse o nosso [site](http://smartdealership.com.br).

Direitos reservados à Smart Dealer Soluções em Software Ltda.

### Requísitos 

* PHP 5.3 ou superior
* Extensões do PHP "php_curl" e "php_openssl"
* Apache 2.2+

### Autenticação

~~~.php
  # include API class
  include_once 'sdapi.class.php';
  
  # client name OR direct instance URL (prima or prima.smartdealer.com.br)
  $env = 'prima';
  
  # login webservice Rest
  $usr = 'primafiat';    
  
  # password webservice Rest (example)
  $pwd = 'unXEmpkV7ush#';     
  
  # init API
  $api = new Smart\Api($env, $usr, $pwd, array());
  
~~~~

### Exemplo de uso

~~~.php
  # call method
  $ret = $api->get('/parts/');
  
  # output (Array)
  array(
    0 => array(
      'codigo' => 0001
      'nome'   => 'Parachoque Dianteiro (Palio ELX)',
      'modelo' => 'MCBSA-12',
      'preco'  => 840.00,
      'qtd'    => 10,
      'fab'    => '1 - FIAT' 
    ),
    1 => array(
      'codigo' => 0002
      'nome'   => 'Parachoque Traseiro (Palio ELX/EX)',
      'modelo' => 'MCBSA-15',
      'preco'  => 532.00,
      'qtd'    => 7
      'fab'    => '1 - FIAT' 
    ),
    2 => array(
      'codigo' => 0003
      'nome'   => 'Motor Limpador de Parabrisa (UNO Vivace)',
      'modelo' => 'MCBSA-88',
      'preco'  => 120.00,
      'qtd'    => 2,
      'fab'    => '1 - FIAT' 
    )
  )
  
~~~~

### Tipos de métodos

#### GET (leitura de dados)

~~~.php
  
  # reading data list
  $api->get('/route/method');
  
  # reading specific data
  $api->get('/route/method/:id');


~~~

#### POST (envio de dados)

~~~.php
  
  $data = array();
  
  # send data (simple)
  $api->post('/route/method/', $data);
  
  # send data with ID param (if required)
  $api->post('/route/method/:id', $data);


~~~

#### DELETE (deleção de registros)

~~~.php
  
  # delete data
  $api->delete('/route/method/:id');


~~~

### Métodos do webservice

##### GET : /config/affiliates/
Lista as filiais do cliente

##### GET : /parts/
Lista o estoque de peças 

##### GET : /parts/providers/
Lista os fabricantes cadastrados

##### POST : /parts/order/ [array]
Registra ou atualiza a reserva de uma peça (e-commerce)
 
##### POST : /parts/notify/ [array]
Registra ou atualiza a lista de notificações, pendências no estoque (e-commerce) 

##### DELETE : /parts/order/:id 
Remove a reserva de uma peça

##### GET : /connect/packs/ 
Lista os pacotes de ofertas disponíveis (connect)

##### GET : /connect/pack/:id 
Lista as ofertas de um determinado pacote (connect)

##### GET : /connect/offers/
Lista todas as ofertas do cliente

### Parâmetros de configuração

~~~.php

  # the API settings
  $settings = array(
    'handle' => 'curl',
    'timeout' => 10,
    'use_ssl' => false,
    'port' => 80,
    'debug' => false,
    'output_format' => 1
  );
  
  # init API (with param settings)
  $api = new Smart\Api($env, $usr, $pwd, $settings);
  
  
~~~

#### handle
Escolha do método/protocolo de conexão com o servidor Restful.

* String: "curl" (padrão), "socket" e "stream"

#### timeout
Tempo máximo da resposta do servidor em segundos.

* Integer: 10 (padrão)

#### use_ssl
Habilitar esta opção se a conexão exigir SSL.

* Bool: false (padrão) 

#### port
Número da porta de conexão com servidor Restful.

* Integer: 80 (padrão) 

#### debug
Para desenvolvedores, se ativa exibe os erros execução e comunicação com o servidor.

* Bool: false (padrão) 

#### output_format
Opção de configuração do formato de resposta do servidor ('JSON' = 1, 'XML' = 2).

* Integer: 1 (padrão) 

### Atualização regular

@Release 1.1 

Nota da versão:

* Adição de feature: configuração para resposta em XML

Observação: os parâmetros dos métodos de envio (POST) serão adicionados futuramente.

### Documentação em arquivo

Faça o download da [documentação](http://smartdealership.com.br/docs/api/sdapi-manual.pdf) em PDF (incompleto).
