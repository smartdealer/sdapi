# Smart Dealer Client API
API de comunicação com o software para revendas e concessionárias Smart Dealer

Para mais informações, acesse o nosso [site](http://smartdealership.com.br).

Direitos reservados à Smart Dealer Soluções em Software Ltda.

### Autenticação

~~~.php
  # include API class
  include_once 'sdapi.class.php';
  
  # URL instance name
  $env = 'prima';
  
  # login webservice Rest
  $usr = 'primafiat';    
  
  # password webservice Rest (example)
  $pwd = 'unXEmpkV7u';     
  
  # init API
  $api = new Smart\Api($env, $usr, $pwd, array());
~~~~

### Exemplo de uso

~~~.php
  # call method
  $ret = $api->post('/parts/');
  
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

### Documentação

Faça o download da [documentação](http://smartdealer.com.br/docs/api/sdapi-manual.pdf) em PDF. 
