<?php

require_once  __DIR__  . '/../DataHelper.php';
class Product
{

    private static $conn;

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

        $person['ptd_desc_ini_date'] =   $person['ptd_desc_ini_date'] ? DataHelper::Data($person['ptd_desc_ini_date']) : null;
        $person['ptd_desc_final_date'] = $person['ptd_desc_final_date'] ? DataHelper::Data($person['ptd_desc_final_date']) : null;
        $person['ptd_price_full'] =   DataHelper::InsereMoeda($person['ptd_price_full']);
        $person['ptd_price_alter'] =   DataHelper::InsereMoeda($person['ptd_price_alter']);

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
             
           /// var_dump( $person['ptd_image']);
            $ptd_image =  ProductForm::PegaURLimage();
            $person['ptd_image'] = $ptd_image;
           // var_dump( $person['ptd_image']);
            $sql = "UPDATE product SET ptd_name = :ptd_name, ptd_description=:ptd_description, ptd_tags = :ptd_tags, ptd_link_alt = :ptd_link_alt, ptd_code = :ptd_code,
                 ptd_image = :ptd_image, ptd_height = :ptd_height, ptd_length= :ptd_length, ptd_depth = :ptd_depth,
                 ptd_weight = :ptd_weight, ptd_price_full = :ptd_price_full, ptd_descont = :ptd_descont,  ptd_price_alter = :ptd_price_alter,
                 ptd_desc_ini_date = :ptd_desc_ini_date, ptd_desc_final_date = :ptd_desc_final_date, ptd_stock= :ptd_stock, ptd_category_id = :ptd_category_id,
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

        return $result->fetch(PDO::FETCH_ASSOC);
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

    public static function optionsCategoriesEdit($id)
    {

        // $conn = self::getConnection();
        $conn = mysqli_connect("localhost", "root", "gugu2018", "trabalho");
        $brandNameResult = mysqli_query($conn, "SELECT * FROM product WHERE pdt_id = {$id}");
        while ($linhasBrands = mysqli_fetch_array($brandNameResult)) {
            $brand_id =  $linhasBrands['ptd_category_id'];
        }
        //var_dump($brandName);
        $productResult =  mysqli_query($conn, "SELECT * FROM categories WHERE  cat_id = {$brand_id}");
        while ($linhasProduct = mysqli_fetch_array($productResult)) {
            $brand_id2 = $linhasProduct['cat_id'];
            $brand_name = $linhasProduct['cat_category'];
        }
        $rows = "<option value='$brand_id2' selected> $brand_name </option>";
        foreach (Product::allCat() as $company) {

            $row = "<option value='{$company['cat_id']}'> {$company['cat_category']} </option>";

            $rows .= $row;
        }
        //var_dump($rows);
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

    public static function optionsBrandsEdit($id)
    {

        // $conn = self::getConnection();
        $conn = mysqli_connect("localhost", "root", "gugu2018", "trabalho");
        $brandNameResult = mysqli_query($conn, "SELECT * FROM product WHERE pdt_id = {$id}");
        while ($linhasBrands = mysqli_fetch_array($brandNameResult)) {
            $brand_id =  $linhasBrands['ptd_brand_id'];
        }
        //var_dump($brandName);
        $productResult =  mysqli_query($conn, "SELECT * FROM brands WHERE brand_id = {$brand_id}");
        while ($linhasProduct = mysqli_fetch_array($productResult)) {
            $brand_id2 = $linhasProduct['brand_id'];
            $brand_name = $linhasProduct['brand_name'];
        }
        $rows = "<option value='$brand_id2' selected> $brand_name </option>";
        foreach (Product::allBrands() as $company) {

            $row = "<option value='{$company['brand_id']}'> {$company['brand_name']} </option>";

            $rows .= $row;
        }
        //var_dump($rows);
        return  $rows;
    }

    public static function optionsUnitEdit($id)
    {

        // $conn = self::getConnection();
        $conn = mysqli_connect("localhost", "root", "gugu2018", "trabalho");
        $brandNameResult = mysqli_query($conn, "SELECT * FROM product WHERE pdt_id = {$id}");
        while ($linhasBrands = mysqli_fetch_array($brandNameResult)) {
            $brand_id =  $linhasBrands['ptd_unit_id'];
        }
        //var_dump($brandName);
        $productResult =  mysqli_query($conn, "SELECT * FROM units WHERE unit_id = {$brand_id}");
        while ($linhasProduct = mysqli_fetch_array($productResult)) {
            $brand_id2 = $linhasProduct['unit_id'];
            $brand_name = $linhasProduct['unit_name'];
        }
        $rows = "<option value='$brand_id2' selected> $brand_name </option>";
        foreach (Product::allUnit() as $company) {

            $row = "<option value='{$company['unit_id']}'> {$company['unit_name']} </option>";

            $rows .= $row;
        }
        //var_dump($rows);
        return  $rows;
    }
}
