<?php

require_once __DIR__ . '/classes/Product.php';
require_once __DIR__  . '/config/config.php';
class ProductForm
{
    private $html;
    private $data;

    public function __construct()
    {

        $this->html = file_get_contents('html/form.html');
        $this->data = [

            'pdt_id' => null,
            'ptd_name' => null,
            'ptd_description'  => null,
            'ptd_tags'  => null,
            'ptd_link_alt' => null,
            'ptd_code' => null,
            'ptd_image' => null,
            'ptd_height' => null,
            'ptd_length' => null,
            'ptd_depth' => null,
            'ptd_weight' => null,
            'ptd_price_full' => null,
            'ptd_descont' => null,
            'ptd_price_alter' => null,
            'ptd_desc_ini_date' => null,
            'ptd_desc_final_date' => null,
            'ptd_stock' => null,
            'ptd_category_id' => null,
            'ptd_brand_id' => null,
            'ptd_unit_id' => null,

        ];
    }


    public static function PegaURLimage()
    {

        if ($_FILES && !empty($_FILES['ptd_image']['name'])) {
            $fileUpload = $_FILES['ptd_image'];

            $allowedTypes = [

                'image/jpg',
                'image/jpeg',
                'image/png'
            ];

            $newFileName =  time() . $fileUpload['name'];  // . mb_strstr($fileUpload['name'], '.');
            $mensagem = 'salvo';
            // var_dump($newFileName);
            if (in_array($fileUpload['type'], $allowedTypes)) {
                if (move_uploaded_file($fileUpload['tmp_name'], __DIR__ . "/uploads/{$newFileName}")) {
                    $caminho =     "/uploads/{$newFileName}";
                    echo "<p class='trigger accept'> Arquivo enviado com  sucesso</p>";
                    return $caminho;
                } else {
                    echo "<p class='trigger error'> Erro inesperado</p>";
                }
            } else {
                echo "<p class='trigger warning'> Tipo de arquivo não permitido </p>";
            }
        }
    } ///fim da funcao PegaURLimage

    public function edit($param)
    {

        try {
            /// var_dump($param);
            $id = (int)$param['id'];
            /// var_dump($id);
            $product = Product::find($id);
            $product['ptd_desc_ini_date'] = date(DATE_BR, strtotime($product['ptd_desc_ini_date']));
            $product['ptd_desc_final_date'] = date(DATE_BR, strtotime($product['ptd_desc_final_date']));
            $product['ptd_image'] = BASE .  $product['ptd_image'];
            var_dump( $product['ptd_image']);

            $this->data = $product;
            
           
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    public function save($param)
    {

        try {

            Product::save($param);
            $this->data = $param;
            print "<div class='trigger trigger-sucess center'><p>Produto salvo com Sucesso!</p></div>";
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    public function show()
    {
        if ($this->data['pdt_id'] <= 0) {
            $this->data['ptd_brand_id'] =  Product::optionsBrands();
        } else {
            $this->data['ptd_brand_id'] = Product::optionsBrandsEdit($this->data['pdt_id']);
        }

        if ($this->data['pdt_id'] > 0) {
            $this->data['ptd_unit_id'] = Product::optionsUnitEdit($this->data['pdt_id']);
        } else {
            $this->data['ptd_unit_id'] = Product::optionsUnit();
        }

        if ($this->data['pdt_id'] > 0) {
            $this->data['pdt_category_id'] = Product::optionsCategoriesEdit($this->data['pdt_id']);
        } else {
            $this->data['pdt_category_id'] = Product::optionsCategories();
        }
       
        

        $this->html = str_replace(

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

                $this->data['pdt_id'],
                $this->data['ptd_name'],
                $this->data['ptd_description'],
                $this->data['ptd_tags'],
                $this->data['ptd_link_alt'],
                $this->data['ptd_code'],
                $this->data['ptd_image'] ?: 'http://localhost:8080/v7-Produto/v7/images/fundo.jpg',
                $this->data['ptd_height'],
                $this->data['ptd_length'],
                $this->data['ptd_depth'],
                $this->data['ptd_weight'],
                $this->data['ptd_price_full'],
                $this->data['ptd_descont'],
                $this->data['ptd_price_alter'],
                
                $this->data['ptd_desc_ini_date'],
                $this->data['ptd_desc_final_date'],
                $this->data['ptd_stock'],

                $this->data['ptd_unit_id'],
                $this->data['pdt_category_id'],
                $this->data['ptd_brand_id']


            ],

            $this->html
        );

        // var_dump($this->data['ptd_brand_id']);

        //$this->html = str_replace($search, $this->data, $this->html);

        print  $this->html;
    }
}
