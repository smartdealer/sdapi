# Smart Dealer Client API
### Use a tecnologia Smart na sua empresa ou agência
### 98% de precisão na detecção automática de modelos/versões e compatibilidade de listas
API e documentação de comunicação (for PHP servers) com a plataforma para revendas e concessionárias Smart Dealer.

[![GPL Licence](https://badges.frapsoft.com/os/gpl/gpl.svg?v=103)](https://opensource.org/licenses/MIT/) [![PHPPackages Rank](http://phppackages.org/p/smartdealer/sdapi/badge/rank.svg)](http://phppackages.org/p/smartdealer/sdapi) ![](https://reposs.herokuapp.com/?path=smartdealer/sdapi&style=flat)

Para mais informações, acesse o nosso [site](http://smartdealership.com.br).

Direitos reservados à Smart Dealer Soluções em Software Ltda.

Caso necessite de acesso a integração antiga, veja o [SD Soap-XML](https://github.com/smartdealer/sdpack).

### Requísitos 

* PHP 5.3 ou superior
* Extensões do PHP "php_curl" e "php_openssl"
* Apache 2.2+

### Use via composer

    composer require smartdealer/sdapi

### Autenticação

~~~.php

  # include API class
  include_once 'src/smart/api.php';
  
  # client name OR direct instance URL (prima or prima.smartdealer.com.br)
  $env = 'prima';
  
  # login webservice Rest
  $usr = 'primafiat';    
  
  # password webservice Rest (example)
  $pwd = 'unXEmpkV7ush#';     
  
  # init API
  $api = new Smart\Api($env, $usr, $pwd, array());
  
~~~~

### Acesso direto (url)

    https://{usuario}:{chave}@{cliente}.smartdealer.com.br/webservice/rest/connect/offers/?format={formato}&template={template}

* {usuario}  = usuário do ws
* {chave}    = chave do ws
* {cliente}  = nome da instância
* {formato}   = formato do saída em JSON ou XML (vide parâmetros de configuração)
* {template} = estrutura de campos da saída 

### Uso em embiente de produção

Será necessário a criação de um login, chave de acesso e a liberação do endereço de IP (servidor onde a API será executada) pela Smart para autenticação no webservice Rest, ambiente de produção.

A solicitação poderá ser feita atravéz deste link: http://bit.ly/2bVryEC

![alt tag](http://smartdealership.com.br/img/api/sd-cad-usuario-integracao.jpg)

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

### Dicionário de dados

Tradução dos campos retornados na consulta das ofertas selecionadas do estoque.

| campo         | tipo         |  descrição  |
| ------------- | ------------- | ------------- |
| id            | integer       | **id** do veículo no estoque
| tipo			| string		| código do tipo (N para novo e U para usado)
| categoria	    | integer		| código da categoria (carro, moto ou caminhão)
| filial        | integer       | **id** da filial, use a rota **/config/affiliates/** para listar
| placa         | string        | placa do veículo (se houver)
| chassi        | string        | chassi do veículo (se houver)
| marca         | string        | descrição da marca 
| modelo_id     | string        | código do modelo
| modelo        | string        | descrição do modelo
| cor_id		| string		| codigo da cor
| cor			| string	 	| descrição da cor
| km			| integer       | quilometragem do veículo
| combustivel   | string        | descrição do combustível
| ano_fabricacao| integer (4)   | ano de facricação do veículo
| ano_modelo    | integer (4)   | ano do modelo do veículo
| promocao		| string        | status do veículo em promoção (S ou N)
| preco			| float			| preço do veículo
| dias_estoque  | integer       | número dos dias em estoque
| observacao    | string        | observações do vendedor/concessionária
| imagens       | array         | lista das URLs das imagens do veículo


#### GET (leitura de dados)


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

#### DELETE (deleção de dados)

~~~.php
  
  $data = array();
  
  # remove data (required ID param) 
  $api->delete('/route/method/:id');
  
~~~  
  

##### Retorno padrão (para uso das rotas HTTP)

~~~.json
{
  "status": 500,
  "errors": [
    "O limite de 1 conta(s) foi atingido. Entre em contato conosco."
  ],
  "response": false
}

~~~

| campo         | tipo         |  descrição  |
| -------------   | ------------ | ------------- |
| status          | integer    | código de retorno
| errors          | array      | listagem de erros (se houver, status 500)
| response        | mixed      | resposta adicional do método

##### Tabela de tradução

| código        | descrição    |
| ------------- | ------------ | 
| 200           | sucesso      | 
| 400           | em manutenção| 
| 500           | error        | 

### Métodos do webservice (configuração)

##### GET : /config/categories/
Lista as categorias de veículos do estoque (carro, moto, caminhão)

| campo         | tipo         |  descrição  |
| ------------- | ------------ | ------------- |
| id            | integer      | id da categoria*
| descricao     | string       | nome da categoria (Ex: Carro)

##### POST : /config/affiliate/
Cadastra um novo cliente/CNPJ no sistema

| campo         | tipo         |  descrição  |
| ------------- | ------------ | ------------- |
| nome          | string       | nome do cliente (Ex: Exemplo Fiat) 
| cnpj          | integer      | cnpj do cliente (14 digitos)
| razao_social  | string       | razão social (Ex: Exemplo Fiat Veículos Ltda.)
| matriz        | boolean      | especifica se cadastro é matriz ou loja principal

##### GET : /config/affiliates/
Lista as filiais/lojas do cliente

| campo         | tipo         |  descrição  |
| ------------- | ------------ | ------------- |
| nome          | string       | nome do cliente (Ex: Exemplo Fiat) 
| cep           | string       | endereço de cep
| cnpj          | integer      | cnpj do cliente (14 digitos)
| razao_social  | string       | razão social (Ex: Exemplo Fiat Veículos Ltda.)
| endereco      | string       | endereço da concessionária/revenda
| bairro        | string       | nome do bairro
| cidade        | string       | nome da cidade
| telefone      | integer      | número do telefone (dd + número)
| responsavel   | string       | nome do contato responsável
| email         | string       | email do cliente
| hashcode      | string       | hash token criação de senha
 
### Métodos do webservice (estoque de peças)

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

### Métodos do webservice (integrador)

##### GET : /connect/channels/
Lista os canais/portais disponíveis para integração

| campo         | tipo         |  descrição  |
| ------------- | ------------ | ------------- |
| id            | integer      | código do canal
| nome          | string       | nome do canal (Ex: Portal iCarros) 
| identificador | string       | nome do drive identificador (Ex: icarros)
| status        | integer      | 1 na fila, 2 em manutenção, 3 disponível

##### GET : /connect/codes/
Lista de tradução dos códigos de resposta dos canais de integração.

Breve exemplo, para ver a lista completa faça uma chamada a rota Rest acima.

| codigo        | descricao        
| ------------- | ------------ | 
| 7             | versão incompatível c/ o ano 
| 13            | a cor informada é inválida   
| 16            | plano ou categoria inválido  
| 21            | limite da categoria esgotado 
| 33            | preço abaixo do permitido (20% FIPE) 
| 300           | combustíveis não carregados

##### POST : /connect/contract/ 
Cria uma configuração de integração (connect)

| campo         | tipo         |  descrição  |
| ------------- | ------------ | ------------- |
| site_id       | integer      | id do canal de integração (vide ~/channels/)
| status        | integer      | 1 atualização automática ativa, 0 desativada
| anuncios      | integer      | total de anúncios do plano (apenas para cálculo)
| nome          | string       | nome de indentificação do contrato
| filial        | integer      | filial a ser lida/publicada (ofertas)
| cnpj          | integer      | cnpj utilizado na conta do portal
| login         | string       | login/email utilizado na conta do portal
| senha         | string       | senha da conta do portal
| segmento      | integer      | categoria principal, vide "/config/categories/"

##### GET : /connect/contracts/
Lista as integrações configuradas (contratos de integração)

| campo         | tipo         |  descrição  |
| ------------- | ------------ | ------------- |
| id            | integer      | código do contrato/integração
| site_id       | integer      | id do canal de integração vide "connect/channels/"
| data_criacao  | string       | data do cadastro da integração
| identificador | string       | nome do canal ou portal integrado (Ex: webmotors)
| nome          | string       | nome de indentificação do contrato
| status        | integer      | 1 atualização automática ativa, 0 desativada
| anuncios      | integer      | total de anúncios do plano (definido no cadastro)
| tot_destaque  | integer      | total de anúncios em destaque (pós sincronização)
| tot_manual    | integer      | anúncios cadastrados pelo portal (pós sincronização)
| login         | string       | login/email utilizado na conta do portal
| senha         | string       | senha da conta do portal
| segmento      | integer      | categoria principal, vide "/config/categories/"
| valido        | boolean      | status operacional da integração (true = integrado)

##### POST : /connect/offer/ 
Cadastra um veículo para publicação em um pacote de ofertas (connect)

| campo         | tipo          |  descrição    |
| ------------- | ------------- | ------------- |
| contrato_id	| integer		| código da integração "/connect/contracts/"
| tipo			| string		| código do tipo (N para novo e U para usado)
| categoria	    | integer		| código da categoria "/config/categories/"
| placa         | string        | placa do veículo (se houver)
| chassi        | string        | chassi do veículo (se houver)
| marca         | string        | descrição da marca 
| modelo        | string        | descrição do mode
| cor			| string	 	| descrição da cor
| portas        | integer       | quantidade de portas do veículo
| transmissao   | string        | descrição da transmissão
| km			| integer       | quilometragem do veículo
| combustivel   | string        | descrição do combustível
| ano_fabricacao| integer (4)   | ano de facricação do veículo
| ano_modelo    | integer (4)   | ano do modelo do veículo
| promocao		| string        | status do veículo em promoção (S ou N)
| preco			| float			| preço do veículo
| observacao    | string        | observações do vendedor/concessionária
| opcionais     | string        | opcionais separados por ";", ex: "ar condicionado;trava;direção" 
| imagens       | array         | lista[0,1,2] das imagens do veículo (código fonte em **BASE64**)

##### GET : /connect/packs/ 
Lista os pacotes de ofertas disponíveis (connect)

| campo         | tipo         |  descrição  |
| ------------- | ------------ | ------------- |
| id            | integer      | código do pacote
| nome          | string       | nome customizado do pacote (Ex: Feirão iCarros) 
| status        | integer      | 1 ativo, 0 bloqueado
| ultimo_envio  | datetime     | data do ultimo envio

##### GET : /connect/pack/:id 
Lista as ofertas de um determinado pacote (connect)

| campo         | tipo         |  descrição  |
| ------------- | ------------- | ------------- |
| id            | integer       | **id** do veículo no estoque
| ordem         | integer       | posição no pacote (ordem de publicação)
| pacote_id     | integer       | código do pacote de ofertas
| driver        | string        | identificador do canal de integração, ex: "icarros"
| tipo			| string		| código do tipo (N para novo e U para usado)
| categoria	    | integer		| código da categoria (carro, moto ou caminhão)
| filial        | integer       | **id** da filial, use a rota **/config/affiliates/** para listar
| placa         | string        | placa do veículo (se houver)
| chassi        | string        | chassi do veículo (se houver)
| marca         | string        | descrição da marca 
| modelo_id     | string        | código do modelo
| modelo        | string        | descrição do modelo
| cor_id		| string		| codigo da cor
| cor			| string	 	| descrição da cor
| km			| integer       | quilometragem do veículo
| combustivel   | string        | descrição do combustível
| ano_fabricacao| integer (4)   | ano de facricação do veículo
| ano_modelo    | integer (4)   | ano do modelo do veículo
| promocao		| string        | status do veículo em promoção (S ou N)
| preco			| float			| preço do veículo
| dias_estoque  | integer       | número dos dias em estoque
| opcionais     | string        | opcionais separados por ";", ex: "ar condicionado;trava;direção" 
| observacao    | string        | observações do vendedor/concessionária
| imagens       | array         | lista das URLs das imagens do veículo
| registro      | datetime      | data da ultima atualização no portal
| ordem         | integer       | número da sequência no pacote
| anuncio_status   | string     | status de publicação no portal 1 = publicado, 0 = offline
| anuncio_envio    | string     | data da ultima sincronização do anúncio
| anuncio_codigo   | string     | código do anúncio no portal
| anuncio_link     | string     | link do anúncio no portal
| status_codigo    | string     | código de retorno
| status_descricao | string     | tradução do retorno

##### GET : /connect/offers/
Lista todas as ofertas do cliente

| campo         | tipo         |  descrição  |
| ------------- | ------------- | ------------- |
| id            | integer       | **id** do veículo no estoque
| ordem         | integer       | posição no pacote (ordem de publicação)
| pacote_id     | integer       | código do pacote de ofertas
| driver        | string        | identificador do canal de integração, ex: "icarros"
| tipo			| string		| código do tipo (N para novo e U para usado)
| categoria	    | integer		| código da categoria (carro, moto ou caminhão)
| filial        | integer       | **id** da filial, use a rota **/config/affiliates/** para listar
| placa         | string        | placa do veículo (se houver)
| chassi        | string        | chassi do veículo (se houver)
| marca         | string        | descrição da marca 
| modelo_id     | string        | código do modelo
| modelo        | string        | descrição do modelo
| cor_id		| string		| codigo da cor
| cor			| string	 	| descrição da cor
| km			| integer       | quilometragem do veículo
| combustivel   | string        | descrição do combustível
| ano_fabricacao| integer (4)   | ano de facricação do veículo
| ano_modelo    | integer (4)   | ano do modelo do veículo
| promocao		| string        | status do veículo em promoção (S ou N)
| preco			| float			| preço do veículo
| dias_estoque  | integer       | número dos dias em estoque
| opcionais     | string        | opcionais separados por ";", ex: "ar condicionado;trava;direção" 
| observacao    | string        | observações do vendedor/concessionária
| imagens       | array         | lista das URLs das imagens do veículo
| registro      | datetime      | data da ultima atualização no portal
| ordem         | integer       | número da sequência no pacote
| anuncio_status   | string     | status de publicação no portal 1 = publicado, 0 = offline
| anuncio_envio    | string     | data da ultima sincronização do anúncio
| anuncio_codigo   | string     | código do anúncio no portal
| anuncio_link     | string     | link do anúncio no portal
| status_codigo    | string     | código de retorno
| status_descricao | string     | tradução do retorno

##### DELETE : /connect/offer/:id
Remove a oferta do pacote e do portal (pós sincronização automática)

| campo         | tipo         |  descrição  |
| ------------- | ------------- | ------------- |
| id            | integer       | **id** da oferta no pacote

A flag :id deverá ser substituída pelo código da oferta, ex: "/connect/offer/1".

##### DELETE : /connect/contract/:id 
Remove uma configuração de integração e seus pacotes (contrato)

| campo         | tipo         |  descrição  |
| ------------- | ------------- | ------------- |
| id            | integer       | **id** do contrato

A flag :id deverá ser substituída pelo código do contrato, ex: "/connect/contract/1".

### Parâmetros de configuração

~~~.php

  # the API settings
  $settings = array(
    'handle' => 'curl',
    'timeout' => 10,
    'use_ssl' => false,
    'port' => 80,
    'debug' => false,
    'output_format' => 1,
    'output_compile' => true
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
Para desenvolvedores: se ativa, exibe erros de execução e comunicação com o servidor.

* Bool: false (padrão) 

#### output_format
Opção de configuração do formato de resposta do servidor ('JSON' = 1, 'XML' = 2).

* Integer: 1 (padrão) 

#### output_compile
Se desativada, mostra a resposta literal do servidor em XML ou JSON.

* Bool: true (padrão) 
 
 
### Integração com portais

Fluxo de interação com o webservice Smart via Api na integração com portais automotivos.

![alt tag](http://smartdealership.com.br/img/api/fluxograma-integracao-via-api.png)

### Atualização regular

@Release 1.6

Nota da versão:

Nenhuma.
