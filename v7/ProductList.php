<?php

require_once __DIR__ . '/classes/Product.php';

class  ProductList
{
    private $html;

    public function __construct()
    {
        $this->html = file_get_contents('html/list.html');
    }

    public function delete($param)
    {
        try {

            $id = (int)$param['id'];
            Product::delete($id);
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    public function load()
    {
        try {
            $rows = '';
            foreach (Product::all() as $company) {
                $row = file_get_contents('html/company.html');

                $row = str_replace(
                    [
                        '{company_id}',
                        '{company_name}',
                        '{company_fantasy}',
                        '{company_cnpj}',
                        '{company_cep}',
                        '{company_address}',
                        '{company_number}',
                        '{company_district}',
                        '{company_phone}',
                        '{company_mail}',
                        '{company_city}',
                        '{company_state}'
                    ],
                    [
                        $company['company_id'],
                        $company['company_name'],
                        ($company['company_fantasy'] ?: $company['company_name']),
                        $company['company_cnpj'],
                        $company['company_cep'],
                        $company['company_address'],
                        $company['company_number'],
                        $company['company_district'],
                        $company['company_phone'],
                        $company['company_mail'],
                        $company['company_city'],
                        $company['company_state']
                    ],
                    $row
                );

                $rows .= $row;
            }
            $this->html = str_replace(
                '{company}',
                $rows,
                $this->html
            );
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    public function show()
    {
        $this->load();
        print $this->html;
    }


   

}
