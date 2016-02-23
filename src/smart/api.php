<?php

class Modelcatalogsmartws extends Model {

    private $api, $debug;

    public function connect() {

        // load de SD Api
        $this->load->library('api/sdapi.class');

        // set WS settings (change config to view in future)
        $url = (DEV_MODE && false) ? WS_DEVELOPMENT : 'grupotoniello';
        $usr = 'sd_toniello';
        $pwd = 'Ah783oshFsk386740Jhsdjg3973hs';

        // instance Api
        $this->api = new Smart\Api($url, $usr, $pwd, array('gzip' => true));

        // debug mode
        $this->debug = true;
    }

    public function registerOrder($data) {

        // connect from WS
        $this->connect();

        // set fields
        $ret = $this->api->post('/parts/order/', $data);

        // debug
        $this->showError($ret);

        // return
        return (bool) !empty($ret->status) and stristr($ret->status, 'success');
    }

    public function deleteOrder($id) {

        // connect from WS
        $this->connect();

        // set fields
        $ret = $this->api->delete('/parts/order/' . $id);

        // debug
        $this->showError($ret);

        // return
        return (bool) !empty($ret->status) and stristr($ret->status, 'success');
    }

    public function getProviders() {

        // connect from WS
        $this->connect();

        // set fields
        $ret = $this->api->get('/parts/provider/');

        // debug
        $this->showError($ret);

        // return
        return $ret;
    }

    public function getParts() {

        // connect from WS
        $this->connect();

        // set fields
        $ret = $this->api->get('/parts/');

        // debug
        $this->showError($ret);

        // return
        return $ret;
    }

    public function getLocations() {

        // connect from WS
        $this->connect();

        // set fields
        $ret = $this->api->get('/config/affiliates/');

        // debug
        $this->showError($ret);

        // return
        return $ret;
    }

    public function getPending() {

        // exec
        $data = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_pending` p WHERE p.status = 1");

        // return
        return (isset($data->rows)) ? $data->rows : array();
    }

    public function changePending($product_id, $data, $sync = false) {

        // get product info
        $pro_info = $this->db->query("SELECT p.sd_product_id, p.height, p.length, p.width, p.weight FROM `" . DB_PREFIX . "product` p WHERE p.product_id = '{$product_id}'");
        $uKey = implode('_', array_slice(explode('_', $pro_info->row['sd_product_id']), 0, 2));

        // get manufacturer info		
        $pen_info = $this->db->query("SELECT p.sd_product_id, p.product_id, p.customer_id, SUM(p.quantity) AS `quantity`, p.location, p.pending_id, m.code AS `manufacturer_id` FROM `" . DB_PREFIX . "order_pending` p LEFT JOIN `" . DB_PREFIX . "manufacturer` m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.sd_product_id = '{$uKey}' AND p.status = 1");
        
        $_heigth = (float) ($sync ? $pro_info->row['height'] : $data['height']);
        $_length = (float) ($sync ? $pro_info->row['length'] : $data['length']);
        $_width = (float) ($sync ? $pro_info->row['width'] : $data['width']);
        $_weight = (float) ($sync ? $pro_info->row['weight'] : $data['weight']);

        if (!empty($pen_info->row['customer_id'])) {

            // prepare main key
            $uKey = trim($pen_info->row['sd_product_id']);

            $stock_info = $this->db->query("SELECT sum(p.quantity) AS total FROM `" . DB_PREFIX . "product` p WHERE p.sd_product_id like '" . $uKey . "%' ");
            $quantity = $stock_info->row['total'];

            foreach ($pen_info->rows as $pen_data) {

                // get customer info		
                $cus_info = $this->db->query("SELECT customer_id, firstname, email, sha1(password) AS `password` FROM `" . DB_PREFIX . "customer` p WHERE p.customer_id = '{$pen_data['customer_id']}' ");

                if (!empty($cus_info->row['customer_id']) && $uKey && strstr($uKey, '_')) {

                    // client info
                    $customer_name = $cus_info->row['firstname'];
                    $email = $cus_info->row['email'];

                    $cub_valid = !empty($_heigth) && !empty($_length) && !empty($_width) && !empty($_weight);
                    $qtd_valid = $quantity >= ((int) $pen_data['quantity']);
     
                    // check pendencies (send user notification)
                    if ($cub_valid && $qtd_valid) {

                        // get language data
                        $this->load->language('mail/order');

                        // message settings
                        $subject = $this->config->get('config_name') . ': ' . $this->language->get('text_notify_subject');
                        $message = $this->language->get('text_notify_message');

                        $desc = current($data['product_description']);

                        if (isset($desc['name'])) {

                            // change status on Smart
                            $params = array(
                                'codigo_fabricante' => $pen_info->row['manufacturer_id'],
                                'codigo_peca' => $pen_info->row['sd_product_id'],
                                'codigo_cliente' => $pen_info->row['customer_id'],
                                'id_produto' => $pen_info->row['product_id'],
                                'codigo_pendencia' => $pen_info->row['pending_id'],
                                'filial' => $pen_info->row['location'],
                                'quantidade' => $pen_info->row['quantity'],
                                'status' => 2
                            );

                            $token = base64_encode($cus_info->row['email'] . ':' . $cus_info->row['password']);
                            
                            // connect from WS
                            $this->connect();
                            
                            // IMPORTANTE: NOTIFY CUSTOMER, AVALIABLE PRODUCTS
                            if ($this->api->post('/parts/notify/', $params)) {

                                // fill product description
                                $product = '<strong>Fabricante: </strong/>' . $data['manufacturer'] . '<br />';
                                $product.= '<strong>Nome da Peça: </strong> ' . $desc['name'] . '<br />';
                                $product.= '<strong>Modelo: </strong/>' . $data['model'] . '<br />';
                                $product.= '<strong>Qtd. Solicitada: </strong/>' . $pen_data['quantity'] . '<br />';

                                // replace data (pattern)
                                $pattern['{#store-name#}'] = $this->config->get('config_name');
                                $pattern['{#customer-name#}'] = $customer_name;
                                $pattern['{#product#}'] = $product;
                                $pattern['{#link#}'] = $this->url->link('account/login', '&oc_access_token=' . $token . '&product_id=' . $params['id_produto'] . '&product_qtd=' . $pen_data['quantity'], 'FORCESTORE');
                                $pattern['{#config-mail#}'] = $this->config->get('config_email');

                                // prepare message
                                $message = str_replace(array_keys($pattern), array_values($pattern), $message);

                                // smtp settings
                                $mail = new Mail();
                                $mail->charset = 'utf8';
                                $mail->protocol = $this->config->get('config_mail_protocol');
                                $mail->parameter = $this->config->get('config_mail_parameter');
                                $mail->hostname = $this->config->get('config_smtp_host');
                                $mail->username = $this->config->get('config_smtp_username');
                                $mail->password = $this->config->get('config_smtp_password');
                                $mail->port = $this->config->get('config_smtp_port');

                                // set user to send
                                $mail->setFrom($this->config->get('config_email'));
                                $mail->setSender($this->config->get('config_name'));
                                $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
                                $mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));

                                if ($email && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
                                    $mail->setTo($email);
                                    $mail->send();
                                }

                                // change pending status	
                                $this->db->query("UPDATE `" . DB_PREFIX . "order_pending` p SET status = 2 WHERE p.sd_product_id = '{$uKey}' ");
                            }
                        }
                    }
                }
            }
        }

        // change auto change dim/size (for simular products)	
        $this->db->query("UPDATE `" . DB_PREFIX . "product` SET height = '" . $_heigth . "', length = '" . $_length . "', width = '" . $_width . "', weight = '" . $_weight . "' WHERE sd_product_id like \"" . $uKey . "%\" ");
    }

    private function showError($ret) {

        $a = $this->api->getError();

        if ($this->debug) {

            // see error
            echo "<pre>";
            var_dump($a, $ret);

            // kill
            exit(0);
        }
    }

}