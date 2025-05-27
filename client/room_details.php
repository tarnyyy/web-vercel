<?php
error_reporting(E_ALL & ~E_WARNING);
session_start();
include('../admin/config/config.php');
include('../admin/config/checklogin.php');
require('../admin/inc/alert.php');

// Fetch site settings
$query = "SELECT * FROM site_settings LIMIT 1";
$result = $mysqli->query($query);
$settings = $result->fetch_assoc();

$room_id = $_GET['room_id'];
$client_id = $_SESSION['client_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($settings['site_name']); ?> | Room Details</title>
  <?php if (!empty($settings['site_favicon'])): ?>
    <link rel="icon" type="image/png" href="../admin/dist/img/logos/<?php echo htmlspecialchars($settings['site_favicon']); ?>">
  <?php endif; ?>
  <!-- Import Links -->
  <?php require('./inc/links.php'); ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <!-- Custom CSS -->
  <style>
    :root {
      --primary-color: <?php echo $settings['site_primary_color']; ?>;
      --hover-color: <?php echo $settings['site_hover_color']; ?>;
      --bg-color: <?php echo $settings['site_bg_color']; ?>;
    }

    body {
      font-family: 'Poppins', sans-serif;
      line-height: 1.6;
    }

    /* Section Headers */
    .miniTitle {
      color: var(--primary-color);
      font-size: 1.1rem;
      font-weight: 600;
      letter-spacing: 2px;
      margin-bottom: 1rem;
    }

    .bigTitle {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 2rem;
      color: #333;
    }

    /* Room Details Styling */
    .roomContainer {
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      height: 500px;
      margin-top: 50px;
    }

    .roomContainer img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .roomContainer:hover img {
      transform: scale(1.05);
    }

    .contentPara {
      font-size: 1.1rem;
      color: #555;
      line-height: 1.8;
      margin-bottom: 1rem;
    }

    /* Room Cards */
    .card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border: none;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      border-radius: 15px;
      overflow: hidden;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .card-img-top {
      border-top-left-radius: 15px;
      border-top-right-radius: 15px;
      height: 200px;
      object-fit: cover;
    }

    .cardRoomTitle {
      font-size: 1.4rem;
      font-weight: 600;
      color: #333;
      margin: 1rem 0;
    }

    .cardRoomDescription {
      color: #666;
      font-size: 0.95rem;
    }

    /* Buttons */
    .btnAddCategory {
      background-color: var(--primary-color);
      color: white;
      padding: 12px 25px;
      border-radius: 8px;
      transition: all 0.3s ease;
      border: none;
      text-decoration: none;
      display: inline-block;
    }

    .btnAddCategory:hover {
      background-color: var(--hover-color);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      color: white;
    }

    /* Price Styling */
    .newBigTitle {
      font-size: 2rem;
      font-weight: 700;
      color: var(--primary-color);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .bigTitle {
        font-size: 2rem;
      }

      .roomContainer {
        height: 300px;
      }
    }

    @media (max-width: 576px) {
      .bigTitle {
        font-size: 1.8rem;
      }

      .contentPara {
        font-size: 1rem;
      }
    }
  </style>
</head>

<body>
  <!-- Navigation -->
  <?php require('./inc/nav.php'); ?>

  <?php
  $ret = "SELECT * FROM rooms WHERE room_id = '$room_id'";
  $stmt = $mysqli->prepare($ret);
  $stmt->execute();
  $res = $stmt->get_result();
  while ($row1 = $res->fetch_object()) {
  ?>
    <div class="container-fluid">
      <div class="row">
        <div class="container mt-5 mb-5 m-auto">
          <div class="col-lg-12 m-auto d-flex justify-content-center mt-5">
            <div class="col-4 mt-5">
              <div class="roomContainer">
                <img src="../admin/dist/img/<?php echo htmlspecialchars($row1->room_picture); ?>" alt="<?php echo htmlspecialchars($row1->room_name); ?>">
              </div>
            </div>
            <div class="col-4 p-4 mt-5">
              <p class="miniTitle"><?php echo htmlspecialchars($row1->room_category); ?></p>
              <h5 class="bigTitle mb-0" style="font-size: 30px;"><?php echo htmlspecialchars($row1->room_name); ?></h5>
              <p class="contentPara"><b><?php echo htmlspecialchars($row1->room_status); ?></b></p>
              <hr class="mb-3">
              <p class="contentPara">
                <b>DESCRIPTION:</b> <br>
                <?php echo htmlspecialchars($row1->room_description); ?>
              </p>
              <p class="contentPara mb-0">
                <b>ADULT:</b> &nbsp;&nbsp; <?php echo htmlspecialchars($row1->room_adult); ?>
              </p>
              <p class="contentPara mb-5">
                <b>CHILD:</b> &nbsp;&nbsp; <?php echo htmlspecialchars($row1->room_child); ?>
              </p>
              <div class="mt-3">
                <p class="contentPara mb-0">
                  <b>PRICE:</b> &nbsp;&nbsp;
                </p>
                <h5 class="newBigTitle mb-4">₱ <?php echo htmlspecialchars($row1->room_price); ?></h5>&nbsp;&nbsp;
              </div>
              <form method="POST">
                <div class="col-4 mt-2 d-grid">
                  <?php if (isset($_SESSION['client_id'])) { ?>
                    <a href="room_book.php?room_id=<?php echo htmlspecialchars($row1->room_id); ?>" class="btn btn-primary btnAddCategory someText">Book Now</a>
                  <?php } else { ?>
                    <a href="login.php" class="btn btn-primary btnAddCategory someText">Book Now</a>
                  <?php } ?>
                </div>
              </form>
            </div>
          </div>
        </div>
      <?php } ?>
      </div>

      <!-- Our Rooms -->
      <div class="row" style="background-color:#f5f5f5;">
        <div class="container py-5" style="padding: 20px;">
          <div class="col-lg-8 m-auto text-center py-5">
            <p class="miniTitle">LOOKING FOR A PLACE TO STAY?</p>
            <h5 class="bigTitle mb-5">OTHER ROOMS YOU MAY LIKE</h5>
            <div class="container">
              <div class="col-lg-12 d-flex justify-content-around" style="flex-wrap:wrap;">
                <?php
                $ret = "SELECT * FROM rooms WHERE room_status = 'Available' ORDER BY RAND() LIMIT 6";
                $stmt = $mysqli->prepare($ret);
                $stmt->execute();
                $res = $stmt->get_result();
                while ($row1 = $res->fetch_object()) {
                ?>
                  <div class="card mb-4" style="width: 18rem;">
                    <img src="../admin/dist/img/<?php echo htmlspecialchars($row1->room_picture); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row1->room_name); ?>">
                    <div class="card-body">
                      <p class="miniTitle"><?php echo htmlspecialchars($row1->room_category); ?></p>
                      <h5 class="cardRoomTitle"><?php echo htmlspecialchars($row1->room_name); ?></h5>
                      <p class="cardRoomDescription"><?php echo htmlspecialchars($row1->room_description); ?></p>
                      <div class="container d-flex justify-content-center">
                        <p class="cardRoomDescription"><b>ADULTS: </b> <?php echo htmlspecialchars($row1->room_adult); ?></p>
                        &nbsp; &nbsp;
                        <p class="cardRoomDescription"><b>CHILD: </b> <?php echo htmlspecialchars($row1->room_child); ?></p>
                      </div>
                    </div>
                    <div class="mb-3 mt-0">
                      <a href="room_details.php?room_id=<?php echo htmlspecialchars($row1->room_id); ?>" class="btn btn-primary btnAddCategory someText">Check More Details</a>
                    </div>
                  </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</body>

</html>