<?php
require_once(__DIR__.'/../dbconfig.php');
require_once(__DIR__.'/../config.php');
require_once(__DIR__.'/../classes/class.user.php');

class NOTIFICATIONS
{
  public function __construct()
  {
    $database = new Database();
    $db = $database->dbConnection();
    $this->conn = $db;
    $c = "4444"; $n = "5";
    $this->updateOverLimit($c,$n);
  }
    		public function ex(){
    			echo '/ntesttttttttttttt';
    		}

        public function updateOverLimit($course_id, $n_day_limit){
          $stmt = $this->conn->prepare("SELECT presence.student, presence.course, presence.date, users.user_id, users.id_number,
                                               users.first_name, users.last_name, users.email, users.phone, courses.course_id,
                                               courses.course_name
            FROM presence
            INNER JOIN courses ON presence.course = courses.course_id
            INNER JOIN users ON users.user_id = presence.student
            WHERE DATE > DATE_SUB( NOW( ) , INTERVAL 2024 HOUR )  -- 24 hours for all of todays data
            GROUP BY CONCAT( presence.course, users.id_number ) ");
          $stmt->execute(array(':course_id' => $course_id, ':day_limit' => $n_day_limit));
          $result = $stmt->fetchall(PDO::FETCH_ASSOC);

          //Email information
            $admin_email = "someone@example.com";
            $email = "alsade15@gmail.com";
            $subject = "missed";
            $comment = "content";

            //send email
            mail($admin_email, "$subject", $comment, "From:" . $email);

            //Email response
            echo "Thank you for contacting us!";


        }
}
// {
// 	// protected $conn;
// 	// public function __construct()
// 	// {
// 	// 	$database = new Database();
// 	// 	$db = $database->dbConnection();
// 	// 	$this->conn = $db;
//   // }
//
//
//

//
//
// }
