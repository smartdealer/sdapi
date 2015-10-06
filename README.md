# Smart Dealer Client API
API de comunicação com o software para revendas e concessionárias Smart Dealer

Para mais informações, acesse o nosso [site](http://smartdealership.com.br).

Direitos reservados à Smart Dealer Soluções em Software Ltda.

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
Delete a reserva de uma peça

##### GET : /connect/packs/ 
Lista os pacotes de ofertas disponíveis (connect)

##### GET : /connect/pack/:id 
Lista as ofertas de um determinado pacote (connect)

##### GET : /connect/offers/
Lista todas as ofertas do cliente

   Observação:  os parâmetros dos métodos de envio (POST) serão adicionados no próximo update.

### Documentação em arquivo

Faça o download da [documentação](http://smartdealership.com.br/docs/api/sdapi-manual.pdf) em PDF (incompleto).
