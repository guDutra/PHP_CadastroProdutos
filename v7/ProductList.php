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
                $row = file_get_contents('html/product.html');

                $row = str_replace(
                    [
                        '{pdt_id}',
                        '{ptd_name}',
                        '{ptd_description}',
                        '{ptd_tags}',
                        '{ptd_link_alt}',
                        '{ptd_code}',
                        '{ptd_image}',
                        '{ptd_height}',
                        '{ptd_length}',
                        '{ptd_depth}',
                        '{ptd_weight}',
                        '{ptd_price_full}',
                        '{ptd_descont}',
                        '{ptd_price_alter}',
                        '{ptd_desc_ini_date}',
                        '{ptd_desc_final_date}',
                        '{ptd_stock}',
                        '{ptd_unit_id}',
                        '{ptd_category_id}',
                        '{ptd_brand_id}'
                    ],
                    [
                        $company['pdt_id'],
                        $company['ptd_name'],
                        $company['ptd_description'],
                        $company['ptd_tags'],
                        $company['ptd_link_alt'],
                        $company['ptd_code'],
                        $company['ptd_image'],
                        $company['ptd_height'],
                        $company['ptd_length'],
                        $company['ptd_depth'],
                        $company['ptd_weight'],
                        $company['ptd_price_full'],
                        $company['ptd_descont'],
                        $company['ptd_price_alter'],
                        $company['ptd_desc_ini_date'],
                        $company['ptd_desc_final_date'],
                        $company['ptd_stock'],
                        $company['ptd_unit_id'],
                        $company['ptd_category_id'],
                        $company['ptd_brand_id'],

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
