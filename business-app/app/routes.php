<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

function getConnection(){
    $servername = "localhost";
    $username = "admin";
    $password = "admin";
    $dbname = "business_db";


    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully";
    
    return $conn;
}


return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('It works!');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });

    $app->get('/test', function (Request $request, Response $response) {
        $response->getBody()->write('test works!');
        return $response;
    });


    $app->get('/catalogWrite', function (Request $request, Response $response) {
        
        $conn = getConnection();

        $sql = "INSERT INTO catalogs (id_catalog, catalog_name, availability, catalog_price) 
        VALUES ('CAT-00005', 'OTONO', true, 550.0);";

        if ($conn->query($sql) === TRUE) {
            //echo "New record created successfully";
            $response->getBody()->write('Write Successful');
        } else {
            //echo "Error: " . $sql . "<br>" . $conn->error;
            $response->getBody()->write("Error: " . $sql . "<br>" . $conn->error);
        }
        $conn->close();  
        
        return $response;
    });

    $app->get('/productWrite', function (Request $request, Response $response) {
        
        $conn = getConnection();

        $sql = "INSERT INTO products (id_product, product_name, id_catalog, availability, product_price, product_color) 
        VALUES ('ZAP-00002','Zapatos Corneolli', 'CAT-00001', true, 420.0, 'black');";

        if ($conn->query($sql) === TRUE) {
            //echo "New record created successfully";
            $response->getBody()->write('Write Successful');
        } else {
            //echo "Error: " . $sql . "<br>" . $conn->error;
            $response->getBody()->write("Error: " . $sql . "<br>" . $conn->error);
        }
        $conn->close();  
        
        return $response;
    });

    $app->get('/login', function (Request $request, Response $response) {
        
        $conn = getConnection();

        $pass = '12345';

        $encryptedPassword = crypt($pass, 'rl'); 


        $sql = "SELECT * FROM business_db.user WHERE business_db.user.username='armandoa'";

        $result = $conn->query($sql);
        echo $result;
        $array = mysqli_fetch_assoc($result);
        $password = $array['password'];

        if ($result === TRUE) {
            //echo "New record created successfully";
            $row = $result->fetch_object();
            if(hash_equals($password, crypt($pass, $password))){
                $response->getBody()->write('Login Successful');
            }else{
                $response->getBody()->write('Wrong password');
            }
            
        } else {
            //echo "Error: " . $sql . "<br>" . $conn->error;
            $response->getBody()->write("Error: " . $sql . "<br>" . $conn);
        }
        $conn->close();  
        
        return $response;
    });

    $app->get('/user', function (Request $request, Response $response) {
        
        $password = '12345';

        $encryptedPassword = crypt($password, 'rl'); 

        $conn = getConnection();

        $sql = "INSERT INTO user (username, password, name, surname, user_type_id) 
        VALUES ('armandxxxx', '{$encryptedPassword}}', 'Jon', 'Perez', 1);";

        if ($conn->query($sql) === TRUE) {
            //echo "New record created successfully";
            $response->getBody()->write('Write Successful');
        } else {
            //echo "Error: " . $sql . "<br>" . $conn->error;
            $response->getBody()->write("Error: " . $sql . "<br>" . $conn->error);
        }
        $conn->close();  
        
        return $response;
    });

    $app->get('/catalogDelete', function (Request $request, Response $response) {
        
        $conn = getConnection();

        // sql to delete a record
        $sql = "DELETE FROM catalogs WHERE id_catalog='CAT-00002'";

        if ($conn->query($sql) === TRUE) {
            //echo "Record deleted successfully";
            $response->getBody()->write('Write Successful');

        } else {
            //echo "Error deleting record: " . $conn->error;
            $response->getBody()->write("Error: " . $sql . "<br>" . $conn->error);

        }

        $conn->close();

        return $response;
    });

    $app->get('/productDelete', function (Request $request, Response $response) {
        
        $conn = getConnection();

        // sql to delete a record
        $sql = "DELETE FROM products WHERE id_product='ZAP-00002'";

        if ($conn->query($sql) === TRUE) {
            //echo "Record deleted successfully";
            $response->getBody()->write('Write Successful');

        } else {
            //echo "Error deleting record: " . $conn->error;
            $response->getBody()->write("Error: " . $sql . "<br>" . $conn->error);

        }

        $conn->close();

        return $response;
    });

    //p
    $app->get('/productUpdate', function (Request $request, Response $response) {
        
        $conn = getConnection();

        $sql = "UPDATE products SET id_product='ZAP-00001', product_name='Zapatillas Andrea', id_catalog='CAT-00001', 
        availability=false, product_price=500, product_color='red' WHERE id_product='ZAP-00002'";

        if ($conn->query($sql) === TRUE) {
            //echo "Record updated successfully";
            $response->getBody()->write('Write Successful');

        } else {
            //echo "Error updating record: " . $conn->error;
            $response->getBody()->write("Error: " . $sql . "<br>" . $conn->error);

        }

        $conn->close();
        
        return $response;
    });

    $app->get('/catalogUpdate', function (Request $request, Response $response) {
        
        $conn = getConnection();

        $sql = "UPDATE catalogs SET id_catalog='CAT-00007', catalog_name='Invierno', 
        availability=false, catalog_price=700 WHERE id_catalog='CAT-00001'";

        if ($conn->query($sql) === TRUE) {
            //echo "Record updated successfully";
            $response->getBody()->write('Write Successful');
        } else {
            //echo "Error updating record: " . $conn->error;
            $response->getBody()->write("Error: " . $sql . "<br>" . $conn->error);

        }

        $conn->close();
        
        return $response;
    });
    
    $app->get('/selectUser', function (Request $request, Response $response) {
         

        $conn = getConnection();

        $sql = "SELECT * from user WHERE username='armandoa'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $pass = '12345';
            while($row = $result->fetch_assoc()) {
                $password=$row['password'];
                if(crypt($pass, $password) == $password){
                    $response->getBody()->write('Login Successful');
                }else{
                 //   $response->getBody()->write('Wrong Login');

                }
            }
        } else {
            echo "0 results";
        }


        $conn->close();  
        
        return $response;
    });

    $app->get('/allCatalogs', function (Request $request, Response $response) {
        
        $conn = getConnection();

        $sql = "SELECT * from catalogs ";

        $result = $conn->query($sql);

        $json = '{';

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                //echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
                $json = $json . "{'id_catalog': {$row['id_catalog']}, 'catalog_name': {$row['catalog_name']}, 
                'availability': {$row['availability']}, 'catalog_price': {$row['catalog_price']}}";
            }
            $json = $json . '}';
            $response->getBody()->write($json);
        } else {
            //echo "0 results";
            $response->getBody()->write("Error: " . $sql . "<br>" . $conn->error);

        }

        $conn->close();
        
        return $response;
    });
};