<?php
require_once "ConDB.php";

class ProductModel{
    static public function createProduct($data){
        $codigoProducto = self::getSku($data["use_sku"]);
        if($codigoProducto == 0){
            //QUERY SQL
            $query = "INSERT INTO productos(use_id, use_nombre, use_sku, use_precio, use_imagen) VALUES (NULL, :use_nombre, :use_sku, :use_precio, :use_imagen)";
            //REALIZO LA CONEXION CON LA BASE DE DATOS
            $stament = Conection::connection()->prepare($query);
            //DATOS QUE SE VAN A ENVIAR A LA BASE DE DATOS
            $stament->bindParam(":use_nombre", $data["use_nombre"], PDO::PARAM_STR);
            $stament->bindParam(":use_sku", $data["use_sku"], PDO::PARAM_STR);
            $stament->bindParam(":use_precio", $data["use_precio"], PDO::PARAM_STR);
            $stament->bindParam(":use_imagen", $data["use_imagen"], PDO::PARAM_STR);
            //MENSAJE QUE DEVUELVE LA SOLICITUD
            $message = $stament->execute() ? "ok" : Conection::connection() ->errorInfo();
            $stament -> closeCursor();
            $stament = null;
            $query = "";
        }
        else{
            $message = "Ya está registrado el producto";
        }
        return $message;
    }

    static public function deleteProduct($data) {
        // Verifica si el producto existe antes de intentar borrarlo
        $codigoProducto = self::getSku($data["use_sku"]);
        
        if ($codigoProducto != 0) {
            // QUERY SQL
            $query = "DELETE FROM productos WHERE use_sku = :use_sku AND use_nombre = :use_nombre";
    
            // REALIZO LA CONEXIÓN CON LA BASE DE DATOS
            $stament = Conection::connection()->prepare($query);
    
            // DATOS QUE SE VAN A ENVIAR A LA BASE DE DATOS
            $stament->bindParam(":use_sku", $data["use_sku"], PDO::PARAM_STR);
            $stament->bindParam(":use_nombre", $data["use_nombre"], PDO::PARAM_STR);
    
            // MENSAJE QUE DEVUELVE LA SOLICITUD
            $message = $stament->execute() ? "ok" : Conection::connection()->errorInfo();
            
            $stament->closeCursor();
            $stament = null;
            $query = "";
        } else {
            $message = "El producto no existe";
        }
    
        return $message;
    }
    

    static public function updateProduct($data) {
        // Verifica si el producto existe antes de intentar actualizarlo
        $codigoProducto = self::getSku($data["use_sku"]);
        
        if ($codigoProducto != 0) {
            // QUERY SQL
            $query = "UPDATE productos SET use_nombre = :use_nombre, use_precio = :use_precio, use_imagen = :use_imagen WHERE use_sku = :use_sku";
    
            // REALIZO LA CONEXIÓN CON LA BASE DE DATOS
            $stament = Conection::connection()->prepare($query);
    
            // DATOS QUE SE VAN A ENVIAR A LA BASE DE DATOS
            $stament->bindParam(":use_nombre", $data["use_nombre"], PDO::PARAM_STR);
            $stament->bindParam(":use_precio", $data["use_precio"], PDO::PARAM_STR);
            $stament->bindParam(":use_imagen", $data["use_imagen"], PDO::PARAM_STR);
            $stament->bindParam(":use_sku", $data["use_sku"], PDO::PARAM_STR);
    
            // MENSAJE QUE DEVUELVE LA SOLICITUD
            $message = $stament->execute() ? "ok" : Conection::connection()->errorInfo();
            
            $stament->closeCursor();
            $stament = null;
            $query = "";
        } else {
            $message = "El producto no existe";
        }
    
        return $message;
    }

    /*
    $movies=array(
    array('titulo'=>"Allied",'fecha'=>"2016",'duracion'=>"124 min",'genero'=>"Thriller. Drama. Romance",
    'portada'=>$url."allied.jpg"),
    array('titulo'=>"Avengers: Infinity War",'fecha'=>"2018",'duracion'=>"156 min",'genero'=>"Ciencia ficción. Fantástico. Acción",
    'portada'=>$url."avengers_infinity_war.jpg"),
    array('titulo'=>"Enemy",'fecha'=>"2013",'duracion'=>"91 min",'genero'=>"Drama psicológico",
    'portada'=>$url."enemy.jpg"),
    array('titulo'=>"El Francotirador",'fecha'=>"2014",'duracion'=>"132 min",'genero'=>"Bélico. Drama | Biográfico",
    'portada'=>$url."el_francotirador.jpg"),
    array('titulo'=>"The Fighter",'fecha'=>"2010",'duracion'=>"115 min",'genero'=>"Drama Basado en hechos reales",
    'portada'=>$url."the_fighter.jpg"),
    array('titulo'=>"The Wolf of Wall Street",'fecha'=>"2013",'duracion'=>"179 min",'genero'=>"Comedia. Drama",
    'portada'=>$url."the_wolf_of_wall_street.jpg"),
    array('titulo'=>"Underworld",'fecha'=>"2003",'duracion'=>"121 min",'genero'=>"Vampiros. Hombres lobo",
    'portada'=>$url."underworld.jpg"),
    array('titulo'=>"Contracara",'fecha'=>"1997",'duracion'=>"137 min",'genero'=>"Acción. Thriller | Crimen",
    'portada'=>$url."contracara.jpg")
); 
*/
    

    static public function getProduct(){
        $query = "SELECT use_id, use_nombre, use_sku, use_precio, use_imagen FROM productos";
        //echo $query;   
        $stament = Conection::connection()->prepare($query);
        $stament->execute();
        $result = $stament->fetchAll(PDO::FETCH_ASSOC);

        // Convertir el resultado a un array de objetos
        $result = array_map(function($producto){
            return [
                'use_id' => $producto['use_id'],
                'use_nombre' => $producto['use_nombre'],
                'use_sku' => $producto['use_sku'],
                'use_precio' => $producto['use_precio'],
                'use_imagen' => $producto['use_imagen'],
            ];
        }, $result);

        return $result;
    }

    //Metodo para verificar si existe ese SKU en la BD
    static private function getSku($sku){
        $query = "";
        $query = "SELECT use_sku FROM productos WHERE use_sku = '$sku';";
        $stament = Conection::connection()->prepare($query);
        $stament->execute();
        $result = $stament->rowCount();
        return $result;
    }
}