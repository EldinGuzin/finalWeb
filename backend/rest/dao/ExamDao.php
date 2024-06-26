<?php

class ExamDao {

    private $conn;

    /**
     * constructor of dao class
     */
    public function __construct(){
      try {
          $servername = "db1.ibu.edu.ba";
          $username = "webfinal_24";
          $password = "web24finPWD";
          $dbname = "webfinal";
          
          

          $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
          $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          echo "Connected successfully";
      } catch(PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
      }
  }

    /** TODO
     * Implement DAO method used to get customer information
     */
    public function get_customers(){
      try {
        $query = "
            SELECT * FROM customers
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    } catch(PDOException $e) {
        error_log("Error fetching customers data: " . $e->getMessage());
        throw new PDOException("Failed to fetch customers table data.");
    }

    }

    /** TODO
     * Implement DAO method used to get customer meals
     */
    public function get_customer_meals($customer_id) {

      try {
        $sql = "SELECT c.first_name AS name, c.last_name AS surname,
        f.name AS food_name,f.brand AS food_brand, m.created_at as meal_date
         FROM 
          customers c
         INNER JOIN 
          meals m ON c.id = m.customer_id
         INNER JOIN 
          foods f ON m.food_id = f.id  

          WHERE c.id = :customer_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return [
                $result
            ];
        } else {
            return [
                'message' => 'Customer with this id does not exist in database'
            ];
        }
    } catch (PDOException $e) {
        return [
            'message' => 'Database error: ' . $e->getMessage()
        ];
    }

    }

    /** TODO
     * Implement DAO method used to save customer data
     */
    public function add_customer($data){

      try{
      $stmt = $this->conn->prepare("INSERT INTO customers (first_name, last_name, birth_date, status) VALUES (:first_name, :last_name, :birth_date, 1)");
      $stmt->execute([
          'first_name' => $data['first_name'],
          'last_name' => $data['last_name'],
          'birth_date' => $data['birth_date'],
      ]);
    } catch(PDOException $e){
      return [
        'message' => 'Database error: ' . $e->getMessage()
    ];
    }


       

    }

    /** TODO
     * Implement DAO method used to get foods report
     */
    public function get_foods_report(){

      try {
        $query = "
            SELECT f.name AS name, f.brand AS brand, f.image_url AS image,
            (SELECT n.name WHERE n.id = 1) as energy,
            (SELECT fn.quantity WHERE fn.nutrient_id = 1) as quantity,
            (SELECT n.unit WHERE n.id = 1) as unit,
            (SELECT n.name WHERE n.id = 2) as protein,
            (SELECT fn.quantity WHERE fn.nutrient_id = 2) as protein_quantity,
            (SELECT n.unit WHERE n.id = 3) as fat_unit,
            (SELECT n.name WHERE n.id = 3) as fat,
            (SELECT fn.quantity WHERE fn.nutrient_id = 3) as fat_quantity,
            (SELECT n.unit WHERE n.id = 3) as fat_unit


            FROM foods f

            INNER JOIN food_nutrients fn ON f.id = fn.food_id
            INNER JOIN nutrients n ON fn.nutrient_id = n.id

            


        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    } catch(PDOException $e) {
        error_log("Error fetching meals data: " . $e->getMessage());
        throw new PDOException("Failed to fetch meals and other table data.");
    }


    }
}
?>
