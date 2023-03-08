<?php

require_once  __DIR__  . '/../DataHelper.php';
class Product
{

    private static $conn;

   


   /* $getPost = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_BOOLEAN);

    if ($_FILES && !empty($_FILES['file']['name'])) {
        var_dump($_FILES);
        $fileUpload = $_FILES['file'];
        var_dump($fileUpload);
    
    
        $allowedTypes = [
            'image/jpg',
            'image/jpeg',
            'image/png',
            'application/pdf'
        ];
    
        $newFileName = time() . mb_strstr($fileUpload['name'], '.');
    
        if (in_array($fileUpload['type'], $allowedTypes)) {
            if (move_uploaded_file($fileUpload['tmp_name'], __DIR__ . "/uploads/{$newFileName}")) {
                echo "<p class='trigger accept'> Arquivo enviado com  sucesso</p>";
            } else {
                echo "<p class='trigger error'> Erro inesperado</p>";
            }
        } else {
            echo "<p class='trigger warning'> Tipo de arquivo não permitido </p>";
        }
    } elseif ($getPost) {
        echo "<p class='trigger warning'> Parece que o arquivo é mt grande </p>";
    } else {
        echo "<p class='trigger warning'> Selecione um arquivo antes de enviar!</p>";
    }*/
    

    public static function getConnection()
    {
        if (empty(self::$conn)) {
            $connection = parse_ini_file('config/books.ini');
            self::$conn = new PDO(
                "mysql:host={$connection['host']};port={$connection['port']};dbname={$connection['name']}",
                "{$connection['user']}",
                "{$connection['pass']}",
                [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8']
            );
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$conn;
    }

    public static function save($person)
    {
        /*var_dump($person['ptd_image']);
        var_dump($person['ptd_price_alter']);*/



        $person['ptd_desc_ini_date'] =   $person['ptd_desc_ini_date'] ? DataHelper::Data($person['ptd_desc_ini_date']) : null;
        $person['ptd_desc_final_date'] = $person['ptd_desc_final_date'] ? DataHelper::Data($person['ptd_desc_final_date']) : null;
        $person['ptd_price_full'] =   DataHelper::InsereMoeda($person['ptd_price_full']) ;
        $person['ptd_price_alter'] =   DataHelper::InsereMoeda($person['ptd_price_alter']) ;
       
        $ptd_image =  ProductForm::PegaURLimage();
        $person['ptd_image'] = $ptd_image;
 
        $conn = self::getConnection();
        if (empty($person['pdt_id'])) {
            $result = $conn->query("SELECT max(pdt_id) as next FROM product");
            $row = $result->fetch();
            $person['pdt_id'] = (int)$row['next'] + 1;
           // $person['ptd_image'] = 'imagem_null';
           // var_dump($person);

            $sql = "INSERT INTO product
                                    ( 
                  pdt_id ,
                 ptd_name ,
                 ptd_description ,
                 ptd_tags ,
                 ptd_link_alt ,
                 ptd_code ,
                 ptd_image ,
                 ptd_height ,
                 ptd_length ,
                  ptd_depth ,
                  ptd_weight ,
                  ptd_price_full ,
                  ptd_descont ,
                  ptd_price_alter ,
                  ptd_desc_ini_date ,
                   ptd_desc_final_date ,
                   ptd_stock ,
                   ptd_category_id ,
                   ptd_brand_id ,
                   ptd_unit_id) VALUES
                                    ( :pdt_id ,
                                    :ptd_name ,
                                     :ptd_description ,
                                      :ptd_tags ,
                                      :ptd_link_alt , 
                                      :ptd_code ,
                                      :ptd_image ,
                                      :ptd_height ,
                                      :ptd_length ,
                                      :ptd_depth ,
                                      :ptd_weight ,
                                      :ptd_price_full ,
                                      :ptd_descont ,
                                      :ptd_price_alter ,
                                       :ptd_desc_ini_date,
                                        :ptd_desc_final_date ,
                                        :ptd_stock ,
                                        :ptd_category_id ,
                                        :ptd_brand_id , 
                                        :ptd_unit_id)";
        } else {

            $sql = "UPDATE product SET ptd_name = :ptd_name, ptd_description=:ptd_description, ptd_tags = :ptd_tags, ptd_link_alt = :ptd_link_alt, ptd_code = :ptd_code,
                 ptd_image = :ptd_image, ptd_height = :ptd_height, ptd_length= :ptd_length, ptd_depth = :ptd_depth,
                 ptd_weight = :ptd_weight, ptd_price_full = :ptd_price_full, ptd_descont = :ptd_descont, ptd_price_alter = :ptd_price_alter,
                 ptd_desc_ini_date = :ptd_desc_ini_date, ptd_desc_final_date = :ptd_desc_final_date, ptd_stock= :ptd_stock, ptd_category_id = :ptd_category_id
                 ptd_brand_id = :ptd_brand_id, ptd_unit_id = :ptd_unit_id WHERE pdt_id = :pdt_id ";
        }

        $result = $conn->prepare($sql);

        return $result->execute(
            [
                ':pdt_id' => $person['pdt_id'],
                ':ptd_name' => $person['ptd_name'],
                ':ptd_description' => $person['ptd_description'],
                ':ptd_tags' => $person['ptd_tags'],
                ':ptd_link_alt' => $person['ptd_link_alt'],
                ':ptd_code' => $person['ptd_code'],
                ':ptd_image' => $person['ptd_image'],
                ':ptd_height' => $person['ptd_height'],
                ':ptd_length'   => $person['ptd_length'],
                ':ptd_depth' =>  $person['ptd_depth'],
                ':ptd_weight' =>  $person['ptd_weight'],
                ':ptd_price_full' =>  $person['ptd_price_full'],
                ':ptd_descont' => $person['ptd_descont'],
                ':ptd_price_alter'   => $person['ptd_price_alter'],
                ':ptd_desc_ini_date' =>  $person['ptd_desc_ini_date'],
                ':ptd_desc_final_date' =>  $person['ptd_desc_final_date'],
                ':ptd_stock' =>  $person['ptd_stock'],
                ':ptd_category_id'   => $person['ptd_category_id'],
                ':ptd_brand_id' =>  $person['ptd_brand_id'],
                ':ptd_unit_id' =>  $person['ptd_unit_id']

            ]
        );
    }

    /**
     * Busca uma Pessoa pelo seu $id
     * @param $id
     *
     * @return mixed
     */
    public static function find($id)
    {
        $conn = self::getConnection();
        $result = $conn->query("SELECT * FROM product WHERE pdt_id='{$id}'");

        return $result->fetch();
    }

    public static function delete($id)
    {
        $conn = self::getConnection();
        $result = $conn->query("DELETE FROM product WHERE pdt_id='{$id}'");

        return $result;
    }

    public static function all()
    {
        $conn = self::getConnection();
        $result = $conn->query("SELECT * FROM product");

        return $result;
    }

    public static function allUnit()
    {
        $conn = self::getConnection();
        $result = $conn->query("SELECT * FROM units");

        return $result;
    }

    public static function allCat()
    {
        $conn = self::getConnection();
        $result = $conn->query("SELECT * FROM categories");

        return $result;
    }

    public static function allBrands()
    {
        $conn = self::getConnection();
        $result = $conn->query("SELECT * FROM brands");

        return $result;
    }



    public static function optionsUnit()
    {

        $rows = '';
        foreach (Product::allUnit() as $company) {
            $row = "<option  value='{$company['unit_id']}'> {$company['unit_name']} </option>";
            $rows .= $row;
        }


        return  $rows;
    }

    public static function optionsCategories()
    {

        $rows = '';
        foreach (Product::allCat() as $company) {

            $row = "<option value='{$company['cat_id']}'> {$company['cat_category']} </option>";

            $rows .= $row;
        }


        return  $rows;
    }

    public static function optionsBrands()
    {

        $rows = '';
        foreach (Product::allBrands() as $company) {

            $row = "<option value='{$company['brand_id']}'> {$company['brand_name']} </option>";

            $rows .= $row;
        }


        return  $rows;
    }
}