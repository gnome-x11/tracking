<?php
session_start();
ini_set('display_errors', 1);


class Action
{
	private $db;

	public function __construct()
	{
		ob_start();
		include 'db_connect.php';

		$this->db = $conn;
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	function login()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '" . $username . "' and password = '" . md5($password) . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			return 1;
		} else {
			return 3;
		}
	}
	function logout()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}

	function save_user()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if (!empty($password))
			$data .= ", password = '" . md5($password) . "' ";
		$data .= ", type = '$type' ";
		if ($type == 1)
			$establishment_id = 0;
		$data .= ", establishment_id = '$establishment_id' ";
		$chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
		if ($chk > 0) {
			return 2;
			exit;
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users set " . $data);
		} else {
			$save = $this->db->query("UPDATE users set " . $data . " where id = " . $id);
		}
		if ($save) {
			return 1;
		}
	}


	function delete_user()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = " . $id);
		if ($delete)
			return 1;
	}

	function signup()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", contact = '$contact' ";
		$data .= ", address = '$address' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '" . md5($password) . "' ";
		$data .= ", type = 3";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if ($chk > 0) {
			return 2;
			exit;
		}
		$save = $this->db->query("INSERT INTO users set " . $data);
		if ($save) {
			$qry = $this->db->query("SELECT * FROM users where username = '" . $email . "' and password = '" . md5($password) . "' ");
			if ($qry->num_rows > 0) {
				foreach ($qry->fetch_array() as $key => $value) {
					if ($key != 'passwors' && !is_numeric($key))
						$_SESSION['login_' . $key] = $value;
				}
			}
			return 1;
		}
	}

	function save_settings()
	{
		extract($_POST);
		$data = " name = '" . str_replace("'", "&#x2019;", $name) . "' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '" . htmlentities(str_replace("'", "&#x2019;", $about)) . "' ";
		if ($_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/img/' . $fname);
			$data .= ", cover_img = '$fname' ";

		}

		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if ($chk->num_rows > 0) {
			$save = $this->db->query("UPDATE system_settings set " . $data);
		} else {
			$save = $this->db->query("INSERT INTO system_settings set " . $data);
		}
		if ($save) {
			$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
			foreach ($query as $key => $value) {
				if (!is_numeric($key))
					$_SESSION['setting_' . $key] = $value;
			}

			return 1;
		}
	}


	function save_establishment()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", address = '$address' ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO establishments set " . $data);
		} else {
			$save = $this->db->query("UPDATE establishments set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_establishment()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM establishments where id = " . $id);
		if ($delete)
			return 1;
	}
	function save_visitor() {
    extract($_POST);

    if (empty($full_name) || empty($email)) {
        return json_encode(["status" => "error", "message" => "Full Name and Email are Required"]);
    }

    // Generate token and expiry
    $token = bin2hex(random_bytes(16));
    $token_expiry = date('Y-m-d H:i:s', strtotime('+24 hours'));

    $data = "full_name = '$full_name'";
    $data .= ", contact_number = '$contact_number'";
    $data .= ", email = '$email'";
    $data .= ", purpose = '$purpose'";
    $data .= ", establishment_id = " . intval($establishment_id);
    $data .= ", created_at = '$created_at'";
    $data .= ", token = '$token'";
    $data .= ", token_expiry = '$token_expiry'";

    $save = $this->db->query("INSERT INTO visitors SET $data");

    if ($save) {
        $visitor_id = $this->db->insert_id;

        // Generate QR Code with token
        require 'assets/qrcode/phpqrcode/qrlib.php';
        $qrContent = $token;
        $tempDir = 'temp_qr/';
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $qrFile = $tempDir . 'visitor_' . $visitor_id . '.png';
        QRcode::png($qrContent, $qrFile);

        // Send Email
        require 'vendor/autoload.php';
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $email_sent = false;

        try {
            // SMTP Config
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'dex.raromin@gmail.com'; // Replace with your email
            $mail->Password   = 'scnv ntfq vnjb bxov';    // Replace with your app password
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Email
            $mail->setFrom('your_email@gmail.com', 'PLMUN Access Control'); // Update
            $mail->addAddress($email);
            $mail->addAttachment($qrFile);

            $mail->isHTML(true);
            $mail->Subject = 'Hi, this is your PLMUN Access Control QR Code';
            $mail->Body    = 'Please scan this QR code in the entrance kiosk. (Note: This QR code is reusable across entrances, but will expire in 24 hours)';
            $mail->AltBody = 'Attached is your QR code for time in/out.';

            $mail->send();
            $email_sent = true;

            // Clean up temp QR image
            if (file_exists($qrFile)) {
                unlink($qrFile);
            }
        } catch (Exception $e) {
            error_log("Email Error: " . $mail->ErrorInfo);
        }

        return json_encode(array("status" => 1, "visitor_id" => $visitor_id, "email_sent" => $email_sent));
    } else {
        return json_encode(array("status" => 0, "message" => "Failed to save visitor"));
    }
}

function scan_visitor() {
    extract($_POST);

    if (empty($token) || empty($establishment_id)) {
        return json_encode(["status" => "error", "message" => "Token and Establishment ID are required"]);
    }

    // Validate token
    $visitor = $this->db->query("SELECT visitor_id FROM visitors WHERE token = '$token' AND token_expiry > NOW()");
    if ($visitor->num_rows === 0) {
        return json_encode(["status" => "error", "message" => "Invalid or expired QR code"]);
    }

    $visitor_id = $visitor->fetch_assoc()['visitor_id'];
    $establishment_id = intval($establishment_id);

    // Check last log entry
    $last_log = $this->db->query("SELECT log_id, time_out FROM visitor_logs
                                WHERE visitor_id = $visitor_id
                                AND establishment_id = $establishment_id
                                ORDER BY log_id DESC LIMIT 1");

    if ($last_log->num_rows > 0) {
        $log = $last_log->fetch_assoc();
        if (empty($log['time_out'])) {
            // Update time_out
            $this->db->query("UPDATE visitor_logs SET time_out = NOW() WHERE log_id = " . $log['log_id']);
            $action = 'time_out';
        } else {
            // Create new time_in
            $this->db->query("INSERT INTO visitor_logs (visitor_id, establishment_id, time_in)
                            VALUES ($visitor_id, $establishment_id, NOW())");
            $action = 'time_in';
        }
    } else {
        // First entry
        $this->db->query("INSERT INTO visitor_logs (visitor_id, establishment_id, time_in)
                        VALUES ($visitor_id, $establishment_id, NOW())");
        $action = 'time_in';
    }

    return json_encode(["status" => "success", "action" => $action]);
}


	function save_person()
	{
		extract($_POST);
		$data = "";
		// Validate required fields (you can customize this)
		if (empty($firstname) || empty($lastname)) {
			return json_encode(["status" => "error", "message" => "Firstname and Lastname are required."]);
		}

		$data .= "firstname = '$firstname' ";
		$data .= ", middlename = '$middlename' ";
		$data .= ", lastname = '$lastname' ";
		$data .= ", student_id = '$student_id' ";
		$data .= ", address = '$address' ";
		$data .= ", street = '$street' ";
		$data .= ", baranggay = '$baranggay' ";
		$data .= ", city = '$city' ";
		$data .= ", state = '$state' ";
		$data .= ", zip_code = '$zip_code' ";
		$data .= ", year_level = '$year_level' ";
		$data .= ", standing = '$standing' ";
		$data .= ", college = '$college' ";
		$data .= ", course = '$course' ";


		// Handle image upload if a file was uploaded
		if (isset($_FILES['photo']) && $_FILES['photo']['tmp_name'] != '') {
			$upload_dir = 'uploads/';
			if (!is_dir($upload_dir)) {
				mkdir($upload_dir, 0755, true);
			}

			$filename = time() . '_' . basename($_FILES['photo']['name']);
			$filepath = $upload_dir . $filename;

			if (move_uploaded_file($_FILES['photo']['tmp_name'], $filepath)) {
				$data .= ", photo = '$filename' ";
			} else {
				return json_encode(["status" => "error", "message" => "Failed to upload image."]);
			}
		}

		if (!empty($id)) {
			// Update existing person
			$save = $this->db->query("UPDATE persons SET $data WHERE id = $id");
		} else {
			// Insert new person
			$save = $this->db->query("INSERT INTO persons SET $data");
		}

		if ($save) {
			return 1;
		} else {
			return json_encode(["status" => "error", "message" => "Database save failed."]);
		}
	}


	function delete_person()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM persons where id = " . $id);
		if ($delete)
			return 1;
	}
	function save_track()
	{
		extract($_POST);

		// Ensure person_id and establishment_id are valid integers
		if (empty($person_id) || empty($establishment_id)) {
			return json_encode(array("status" => 0, "message" => "Missing person_id or establishment_id"));
		}

		$data = " person_id = " . intval($person_id);
		$data .= ", establishment_id = " . intval($establishment_id);

		if (empty($id)) {
			$save = $this->db->query("INSERT INTO person_tracks SET " . $data);
		} else {
			$save = $this->db->query("UPDATE person_tracks SET " . $data . " WHERE id = " . intval($id));
		}

		if ($save) {
			return json_encode(array("status" => 1, "id" => $id));
		} else {
			return json_encode(array("status" => 0, "message" => "Failed to save track"));
		}
	}

	function delete_track()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM person_tracks where id = " . $id);
		if ($delete) {
			return 1;
		}
	}

	function delete_logs()
	{
		extract($_POST);

		$delete = $this->db->query("DELETE FROM failed_login_attempts");

		if ($delete) {
			return 1;
		}
	}


	function get_pdetails()
	{
		extract($_POST);

		// Query with full details
		$get = $this->db->query("SELECT *,
		CONCAT(lastname, ', ', firstname, ' ', middlename) AS name,
		CONCAT(address, ', ', street, ', ', baranggay, ', ', city, ', ', state, ', ', zip_code) AS caddress
		FROM persons
		WHERE student_id = '$student_id'");

		$data = array();

		if ($get->num_rows > 0) {
			$row = $get->fetch_array();
			$data['status'] = 1;

			// Reset invalid attempts on success
			$_SESSION['invalid_attempts'] = 0;

			// Format and return necessary fields
			$data['name'] = ucwords($row['name']);
			$data['student_id'] = $row['student_id'];
			$data['caddress'] = $row['caddress'];
			$data['year_level'] = $row['year_level'];
			$data['standing'] = $row['standing'];
			$data['photo'] = !empty($row['photo']) ? 'uploads/' . $row['photo'] : null;

			// Optional: return all other fields
			foreach ($row as $k => $v) {
				if (!is_numeric($k) && !isset($data[$k])) {
					$data[$k] = $v;
				}
			}
		} else {
			// Increment failed attempts
			$_SESSION['invalid_attempts'] = ($_SESSION['invalid_attempts'] ?? 0) + 1;

			if ($_SESSION['invalid_attempts'] >= 3) {
				$user_id = $_SESSION['login_id'] ?? 0;
				$establishment_id = $_SESSION['login_establishment_id'] ?? 0;
				$error_message = 'Invalid credential';

				$this->db->query("INSERT INTO failed_login_attempts (establishment_id, error_message, date_created)
				VALUES ('$establishment_id', '$error_message', NOW())");

				$data['status'] = 3;
			} else {
				$data['status'] = 2;
				$data['attempts'] = $_SESSION['invalid_attempts'];
			}
		}

		return json_encode($data);
	}


}
