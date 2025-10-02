<?php
// Include your database connection

$conn = include('../database/db_connection.php');

$orgCover = '';
$orgName = '';
$orgLogo = '';
$statusText = '';

if (isset($_GET['id'])) {
    $orgId = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM organizations WHERE org_id = :orgId LIMIT 1");
    $stmt->execute(['orgId' => $orgId]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($record) {
        $orgName = htmlspecialchars($record['org_name']);
        $orgCover = !empty($record['org_cover']) ? htmlspecialchars($record['org_cover']) : '';
        $orgLogo = !empty($record['org_logo']) ? htmlspecialchars($record['org_logo']) : '';
        $statusText = $record['is_active'] ? 'Active' : 'Inactive';
    } else {
        $orgName = 'Organization not found';
    }
} else {
    $orgName = 'No Organization selected';
}
?>

<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="icon" type="image/c-icon" href="../src/clubnexusicon.ico">
<title><?php echo $orgName; ?> Profile</title>
<style>
  @import url("https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap");
  @import url("https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap");

  * {
    box-sizing: border-box;
  }

  body {
    margin: 0;
    font-family: 'Outfit', sans-serif;
    background: white;
  }

  header {
    position: sticky;
    background-color: #0672a1;
    display: flex;
    align-items: center;
    padding: 13px 20px;
    position: relative;
  }

  header img.logo {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    border: 4px solid white;
    object-fit: cover;
  }

  header h1 {
    color: white;
    font-family: 'Fredoka', sans-serif;
    font-weight: 700;
    font-size: 2rem;
    margin-left: 16px;
    user-select: none;
    flex-grow: 1;
  }

  header button.arrow-btn {
    background: white;
    border: none;
    color: #0672a1;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 8px;
    user-select: none;
    border-radius: 4px;
  }

  header button.arrow-btn:focus {
    outline: none;
  }

  nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 6px solid #0672a1;
    border-bottom: 6px solid #d3cfc9;
    padding: 12px 24px;
    background-color: white;
  }

  nav h3 {
    color: #0672a1;
    font-family: 'Fredoka', sans-serif;
    font-weight: 700;
    font-size: 1.125rem;
    margin: 0;
    user-select: none;
  }

  .tabs {
    display: flex;
    gap: 48px;
    background-color: white;
    font-family: 'Outfit', sans-serif;
  }

  .tab {
    font-family: 'Outfit', sans-serif;
    font-weight: 400;
    font-size: 1rem;
    color: black;
    cursor: pointer;
    padding-bottom: 4px;
    border-bottom: 3px solid transparent;
    user-select: none;
    transition: border-color 0.3s;
    background-color: white;
    outline: none;
    border-top: none;
    border-right: none;
    border-left: none;
  }

  .tab:focus {
    outline: none;
  }

  .tab.active {
    border-bottom-color: #0672a1;
    font-weight: 700;
  }

  .arrow-btn a{
    text-decoration: none;
    color: #0672a1;
  }

  main {
    display: flex;
    flex-wrap: wrap;
    gap: 48px;
    padding: 24px;
    height: calc(100vh - 500px);
    background: rgba(250, 250, 250, 1);
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none;  /* Internet Explorer 10+ */
  }

  main::-webkit-scrollbar {
    display:none;
  }



  section {
    flex: 1 1 320px;
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  .bar {
    background-color: #d3d1d1;
    border: 1px solid rgba(0,0,0,0.3);
    border-radius: 9999px;
  }

  .bar.large {
    height: 48px;
    width: 288px;
  }

  .bar.medium {
    height: 16px;
    width: 256px;
  }

  .bar.medium.short {
    width: 192px;
  }

  .bar-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  @media (max-width: 800px) {
    main {
      flex-direction: column;
      gap: 24px;
      padding: 16px;
    }

    main img {
      width: 100%;
      height: auto;
      max-width: 320px;
    }

    section {
      flex: none;
    }
  }
  .stream {
      max-width: 70%;
      margin: 20px auto;
      border-radius: 8px;
      overflow-y: auto; /* Scroll inside stream */
      height: calc(100vh - 220px); /* Adjust height to fit viewport minus header */
      margin-right: 40px;
      margin-right:40px;
      border-width: 3px;
      border-color: #d3d1d1;

    }
    .stream {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none;  /* IE and Edge */
      }
      .stream::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
      }
    .post-card {
      background-color: white;
      margin-bottom: 30px;
      border-radius: 8px;
      border-color: 1px solid gray;
      transition: box-shadow 0.3s ease;
      outline: 3px solid rgba(134, 132, 132, 0.1);
    }
    .post-card:hover {
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .profile-pic {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-right: 12px;
      flex-shrink: 0;
      object-fit: cover;
    }
    .post-header {
      padding: 16px;
      display: flex;
      align-items: center;
    }
    .post-header h3 {
      margin: 0;
      font-size: 1rem;
      font-weight: 600;
    }
    .post-time {
      color: #65676b;
      font-size: 0.875rem;
    }
    .post-body {
      padding: 16px;
    }
    .post-images-container {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 4px;
      padding: 16px;
    }
    .post-image {
      max-width: 100%;
      max-height: 400px;
      object-fit: cover;
      border-radius: 0 0 px 0px;
      cursor: pointer;
      display: block;
      margin: 0 auto;
    }
    .image-overlay {
      position: relative;
      cursor: pointer;
    }
    .overlay-text {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.6);
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 1.2rem;
      font-weight: 500;
      border-radius: 6px;
    }
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(74, 74, 74, 0.9);
      overflow: auto;
    }
    .modal-content {
      position: relative;
      max-width: 800px;
      margin: 70px auto;
      text-align: center;
    }

    .modal-image {
      max-width: 100%;
      max-height: 80vh;
      object-fit: contain;
      display: block;
      margin: 0 auto;
      border-radius: 8px;
    }
    .close {
      position: absolute;
      top: -10px;
      right: -10px;
      color: white;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
      background: rgba(0,0,0,0.5);
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .close:hover {
      background: rgba(0,0,0,0.8);
    }
    .prev, .next {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      font-size: 2rem;
      color: white;
      background: rgba(251, 251, 251, 0.5);
      border: none;
      padding: 10px;
      cursor: pointer;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .prev { left: 10px; }
    .next { right: 10px; }
    .prev:hover, .next:hover {
      background: rgba(173, 172, 172, 0.8);
      color: black;
    }
    .image-counter {
      margin-top: 10px;
      color: white;
      font-size: 0.9rem;
    }

    [role="tabpanel"] {
      height: calc(100vh);
    }

    #about-panel[hidden],
    #membership-panel[hidden],
    #posts-panel[hidden],
    #events-panel[hidden],
    #officers-panel[hidden],
    #members-panel[hidden] {
      display: none !important;
    }


  
</style>
</head>
<body>
<header>
  <?php if ($orgLogo): ?>
    <img class="logo" src="../src/org-logo/<?php echo $orgLogo; ?>" alt="<?php echo $orgName; ?> Logo" />
  <?php else: ?>
    <img class="logo" src="default-logo.png" alt="Default logo" />
  <?php endif; ?>
  <h1><?php echo $orgName; ?></h1>
  <button class="arrow-btn" aria-label="Go to homepage" id="back-btn" title="Homepage">
    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="27" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
    </svg>
  </button>
</header>
<nav>
  <h3>Organization Profile</h3>
  <div class="tabs" role="tablist" aria-label="Organization sections">
    <button class="tab active" role="tab" aria-selected="true" aria-controls="about-panel" id="about-tab" tabindex="0">About</button>
    <button class="tab" role="tab" aria-selected="false" aria-controls="membership-panel" id="membership-tab" tabindex="-1">Membership</button>
    <button class="tab" role="tab" aria-selected="false" aria-controls="posts-panel" id="posts-tab" tabindex="-1">Achievements</button>
    <button class="tab" role="tab" aria-selected="false" aria-controls="events-panel" id="events-tab" tabindex="-1">Events</button>
    <button class="tab" role="tab" aria-selected="false" aria-controls="officers-panel" id="officers-tab" tabindex="-1">Officers & Adviser</button>
    <button class="tab" role="tab" aria-selected="false" aria-controls="members-panel" id="members-tab" tabindex="-1">Members</button>
  </div>
</nav>

<main >

<section style="height: calc(100vh); padding: 0; scrollbar-width: none; -ms-overflow-style: none; width: 100%;">
<div id="about-panel" role="tabpanel" aria-labelledby="about-tab" style="height: 100%; width: 75%;">
    <?php
    // Prepare image URLs and alt texts
    $coverImage = !empty($orgCover) ? "../src/org-cover/" . htmlspecialchars($orgCover) : null;
    $logoImage = !empty($orgLogo) ? "../src/org-logo/" . htmlspecialchars($orgLogo) : "default-logo.png";
    $orgNameEscaped = htmlspecialchars($orgName);
    ?>
    <style>
        /* Container for the entire about panel */
        #about-panel {
            min-width: 400px;
            margin: 0 auto;
            background: white;
            font-family: 'Outfit', sans-serif;
            color: #1c1e21;
            padding-bottom: 40px;
        }

        /* Cover photo at top */
        #about-panel .cover-photo {
            width: 100%;
            min-height: 400px;
            max-height: 500px;
            background-color: #ddd;
            background-position: center;
            background-size: cover;
            position: relative;
            border-radius: 12px 12px 12px 12px;
            box-shadow: 0 2px 8px rgb(0 0 0 / 0.15);
        }

        /* Logo circle overlapping cover */
        #about-panel .logo-circle {
            position: absolute;
            bottom: -60px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid white;
            background-color: white;
            overflow: hidden;
            box-shadow: 0 4px 12px rgb(0 0 0 / 0.2);
        }


        #about-panel .logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Content below cover and logo */
        #about-panel .content {
            margin-top: 80px; /* space for logo overlap */
            padding: 0 30px;
        }

        /* Section styling */
        #about-panel .profile-section {
            background: #f5f6f7;
            border-radius: 12px;
            padding: 20px 25px;
            margin-bottom: 25px;
            box-shadow: 0 1px 2px rgb(0 0 0 / 0.1);
        }

        #about-panel .profile-section h3 {
            margin-top: 0;
            margin-bottom: 15px;
            font-weight: 700;
            font-size: 1.4rem;
            border-bottom: 3px solid #0672a1;
            padding-bottom: 8px;
            color: #0672a1;
        }

        #about-panel .profile-section p {
            font-size: 1rem;
            line-height: 1.6;
            color: #050505;
            white-space: pre-wrap;
        }

        #about-panel .profile-section p i {
            color: #65676b;
        }

        #about-panel .profile-info p {
            margin: 12px 0;
            font-size: 1rem;
        }

        #about-panel .profile-info b {
            color: #050505;
        }

        #about-panel .profile-info a {
            color: #1877f2;
            text-decoration: none;
        }

        #about-panel .profile-info a:hover {
            text-decoration: underline;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            #about-panel .logo-circle {
                width: 90px;
                height: 90px;
                bottom: -45px;
                left: 20px;
                border-width: 4px;
            }

            #about-panel .content {
                margin-top: 60px;
                padding: 0 15px;
            }
        }
    </style>

    <!-- Cover photo -->
    <div class="cover-photo" style="background-image: url('<?php echo $coverImage ? $coverImage : '../src/default-cover.jpg'; ?>');" aria-label="Organization Cover Photo">
        <!-- Logo overlapping cover -->
        <div class="logo-circle" aria-label="<?php echo $orgNameEscaped; ?> Logo">
            <img src="<?php echo $logoImage; ?>" alt="<?php echo $orgNameEscaped; ?> Logo" />
        </div>
    </div>

    <!-- Content below -->
    <div class="content" role="main" tabindex="0">
        <!-- Description -->
        <section class="profile-section" aria-labelledby="desc-heading">
            <h3 id="desc-heading">Description</h3>
            <?php if (!empty($record['org_description'])): ?>
                <p><?php echo nl2br(htmlspecialchars($record['org_description'])); ?></p>
            <?php else: ?>
                <p><i>No Description!</i></p>
            <?php endif; ?>
        </section>

        <!-- Mission -->
        <section class="profile-section" aria-labelledby="mission-heading">
            <h3 id="mission-heading">Mission</h3>
            <?php if (!empty($record['org_mission'])): ?>
                <p><?php echo nl2br(htmlspecialchars($record['org_mission'])); ?></p>
            <?php else: ?>
                <p><i>No Mission Statement!</i></p>
            <?php endif; ?>
        </section>

        <!-- Vision -->
        <section class="profile-section" aria-labelledby="vision-heading">
            <h3 id="vision-heading">Vision</h3>
            <?php if (!empty($record['org_vision'])): ?>
                <p><?php echo nl2br(htmlspecialchars($record['org_vision'])); ?></p>
            <?php else: ?>
                <p><i>No Vision Statement!</i></p>
            <?php endif; ?>
        </section>

        <!-- Information -->
        <section class="profile-section profile-info" aria-labelledby="info-heading">
            <h3 id="info-heading">Information</h3>

            <p><b>Office:</b>
                <?php
                if (!empty($record['org_location'])) {
                    echo htmlspecialchars($record['org_location']);
                } else {
                    echo '<i>No permanent Location!</i>';
                }
                ?>
            </p>

            <p><b>Email:</b>
                <?php
                if (!empty(trim($record['org_contact_email']))) {
                    $email = htmlspecialchars($record['org_contact_email']);
                    echo '<a href="mailto:' . $email . '">' . $email . '</a>';
                } elseif (isset($record['org_contact_email']) && trim($record['org_contact_email']) === '') {
                    echo htmlspecialchars($record['org_contact_email']) . ' ';
                } else {
                    echo '<i>Organization has no email.</i>';
                }
                ?>
            </p>

            <p><b>Admin Contact Number:</b>
                <?php
                if (!empty($record['admin_contactnum'])) {
                    echo htmlspecialchars($record['admin_contactnum']);
                } else {
                    echo '<i>NONE</i>';
                }
                ?>
            </p>

            <p><b>Other Site:</b>
                <?php
                if (!empty($record['org_sites'])) {
                    $site = trim($record['org_sites']);
                    if (filter_var($site, FILTER_VALIDATE_URL)) {
                        $url = htmlspecialchars($site);
                        echo '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">' . $url . '</a>';
                    } else {
                        echo htmlspecialchars($site);
                    }
                } else {
                    echo '<i>NONE</i>';
                }
                ?>
            </p>
        </section>
    </div>
</div>




    <!---------ACHIEVEMENT SECTION-------------->
<div id="posts-panel" role="tabpanel" aria-labelledby="posts-tab" hidden>
  <?php
  if (isset($_GET['id'])) {
      $orgId = intval($_GET['id']);

      $query = "
          SELECT
              a.achievement_id,
              a.achievement_description,
              a.created_at,
              a.achievement_files,
              o.org_name,
              o.org_logo,
              o.org_id
          FROM
              achievements AS a
          INNER JOIN
              organizations AS o ON a.org_id = o.org_id
          WHERE
              a.achievement_approve = 1 AND a.org_id = :orgId
          ORDER BY a.created_at DESC
      ";

      $stmt = $conn->prepare($query);
      $stmt->execute(['orgId' => $orgId]);
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

      function getImagesArray($imagesField) {
          if (empty($imagesField)) return [];
          $images = json_decode($imagesField, true);
          if (json_last_error() === JSON_ERROR_NONE && is_array($images)) {
              return $images;
          }
          $urls = array_filter(array_map('trim', explode(',', $imagesField)));
          if (count($urls) === 1 && !empty($urls[0])) {
              return [$urls[0]];
          }
          return $urls;
      }

      if (empty($results)) {
          echo '<div style="text-align:center; margin-top: 40px;">
                  <img src="../src/nopost.png" alt="No achievements found" style="max-width:300px; width:100%; height:auto;" />
                  <p>No achievements found.</p>
                </div>';
      } else {
          echo '<div class="stream">';
          foreach ($results as $post) {
              $images = getImagesArray($post['achievement_files'] ?? '');
              $org_logo = htmlspecialchars($post['org_logo'] ?? '');
              $org_name = htmlspecialchars($post['org_name'] ?? '');
              $created_at_raw = $post['created_at'] ?? '';
              $org_id = htmlspecialchars($post['org_id'] ?? '');

              $formatted_time = '';
              if (!empty($created_at_raw)) {
                  try {
                      $dateTime = new DateTime($created_at_raw);
                      $formatted_time = $dateTime->format('M d, Y \a\t h:i A');
                  } catch (Exception $e) {
                      $formatted_time = 'Invalid Date';
                  }
              }

              $description = nl2br(htmlspecialchars($post['achievement_description'] ?? ''));
              ?>
              <div class="post-card" data-org-id="<?php echo $org_id; ?>">
                  <div class="post-header">
                      <?php if ($org_logo): ?>
                          <img class="profile-pic" src="../src/org-logo/<?php echo $org_logo; ?>" alt="<?php echo $org_name; ?> logo" onerror="this.style.display='none';" />
                      <?php endif; ?>
                      <div>
                          <h3><?php echo $org_name; ?></h3>
                          <span class="post-time"><?php echo $formatted_time; ?></span>
                      </div>
                  </div>
                  <div class="post-body">
                      <p><?php echo $description; ?></p>
                  </div>
                  <div class="post-images-container" data-post-id="<?php echo htmlspecialchars($post['achievement_id']); ?>"
                      data-all-images='<?php echo json_encode($images, JSON_HEX_APOS | JSON_HEX_QUOT); ?>'
                      <?php if (empty($images)) echo 'style="display:none;"'; ?>>
                      <?php
                      if (!empty($images)):
                          $count = count($images);
                          $showCount = min(3, $count);
                          for ($i = 0; $i < $showCount; $i++):
                      ?>
                          <img src="../../admin/form/src/achievement/<?php echo htmlspecialchars($images[$i]); ?>" alt="<?php echo $description ? strip_tags($description) : 'Post image'; ?>" class="post-image" />
                      <?php endfor; ?>

                      <?php if ($count > 3): ?>
                          <div class="image-overlay">
                              <img src="../../admin/form/src/achievement/<?php echo htmlspecialchars($images[3]); ?>" alt="<?php echo $description ? strip_tags($description) : 'Post image'; ?>" class="post-image" />
                              <div class="overlay-text">+<?php echo $count - 3; ?> more</div>
                          </div>
                      <?php endif; ?>
                      <?php endif; ?>
                  </div>
              </div>
              <?php
          }
          echo '</div>'; // close .stream
      }
  } else {
      echo "<p>Organization ID not specified.</p>";
  }
  ?>
  </div>

      <!----POST POP UP IMAGES---->
      <div id="imageModal" class="modal" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Image viewer" style="display:none;">
          <div class="modal-content">
            <button class="close" aria-label="Close modal">&times;</button>
            <button class="prev" aria-label="Previous image">&#10094;</button>
            <button class="next" aria-label="Next image">&#10095;</button>
            <img id="modalImage" src="" alt="" class="modal-image" />
            <div class="image-counter" id="imageCounter"></div>
          </div>
</div>
      <script src="../script/achievement-pop.js"></script>

<div id="membership-panel" role="tabpanel" aria-labelledby="membership-tab" hidden>
    <?php
    // Prepare logo URL and alt
    $logoUrl = !empty($orgLogo) ? "../src/org-logo/" . htmlspecialchars($orgLogo) : "default-logo.png";
    ?>
    <style>
        #membership-panel {
            min-width: 100%;
            margin: 0 auto;
            padding: 20px 15px 40px;
            font-family: 'Outfit', sans-serif;
            color: #1c1e21;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 500px;
            background: #fff;
            box-shadow: 0 4px 12px rgb(0 0 0 / 0.1);
            border-radius: 12px;
        }

        /* Logo circle at top center */
        #membership-panel .logo-wrapper {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 6px 15px rgb(0 0 0 / 0.2);
            margin-bottom: 25px;
            background: white;
            flex-shrink: 0;
        }

        #membership-panel .logo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Card for membership requirements */
        #membership-panel .requirements-card {
            width: 100%;
            background: #f5f6f7;
            border-radius: 12px;
            padding: 25px 30px;
            box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
            margin-bottom: 30px;
            max-height: 320px;
            overflow-y: auto;
        }

        #membership-panel .requirements-card h3 {
            margin-top: 0;
            margin-bottom: 18px;
            font-weight: 700;
            font-size: 1.5rem;
            color: #0672a1;
            border-bottom: 3px solid #0672a1;
            padding-bottom: 6px;
            text-align: center;
        }

        #membership-panel .requirements-card p {
            font-size: 1rem;
            line-height: 1.6;
            white-space: pre-wrap;
            color: #050505;
        }

        #membership-panel .requirements-card p i {
            color: #65676b;
        }

        /* Files list */
        #membership-panel .files-list {
            list-style: none;
            padding: 0;
            margin: 0 0 30px 0;
            max-height: 180px;
            overflow-y: auto;
        }

        #membership-panel .files-list li {
            margin-bottom: 12px;
            text-align: center;
        }

        #membership-panel .files-list a {
            color: #0075A3;
            font-weight: 600;
            text-decoration: none;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: color 0.3s;
        }

        #membership-panel .files-list a:hover {
            color: #004a6e;
            text-decoration: underline;
        }

        /* File icon */
        #membership-panel .files-list a::before {
            content: "📄";
            font-size: 1.2rem;
        }

        /* Apply button container */
        #membership-panel .apply-btn-container {
            width: 100%;
            text-align: center;
            margin-top: auto;
        }

        #membership-panel .apply-btn {
            background-color: #0075A3;
            color: white;
            padding: 14px 40px;
            font-weight: 700;
            font-size: 1.1rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            box-shadow: 0 4px 10px rgb(0 117 163 / 0.4);
            transition: background-color 0.3s, box-shadow 0.3s;
            user-select: none;
        }

        #membership-panel .apply-btn:hover,
        #membership-panel .apply-btn:focus {
            background-color: #005f7a;
            box-shadow: 0 6px 14px rgb(0 95 122 / 0.6);
            outline: none;
        }

        /* Scrollbar styling for requirements and files */
        #membership-panel .requirements-card::-webkit-scrollbar,
        #membership-panel .files-list::-webkit-scrollbar {
            width: 6px;
        }

        #membership-panel .requirements-card::-webkit-scrollbar-thumb,
        #membership-panel .files-list::-webkit-scrollbar-thumb {
            background-color: rgba(0,0,0,0.1);
            border-radius: 3px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            #membership-panel {
                padding: 15px 10px 30px;
            }

            #membership-panel .logo-wrapper {
                width: 110px;
                height: 110px;
                margin-bottom: 20px;
            }

            #membership-panel .requirements-card {
                padding: 20px 20px;
                max-height: 280px;
            }

            #membership-panel .apply-btn {
                width: 100%;
                padding: 14px 0;
                font-size: 1.2rem;
            }
        }
    </style>

    <!-- Logo at top center -->
    <div class="logo-wrapper" aria-label="Organization Logo">
        <img src="<?php echo $logoUrl; ?>" alt="<?php echo htmlspecialchars($orgName); ?> Logo" />
    </div>

    <!-- Membership Requirements Card -->
    <div class="requirements-card" tabindex="0" aria-label="Membership Requirements">
        <h3>Membership Requirements</h3>
        <?php
        if (!empty($record['org_requirements'])) {
            echo '<p>' . nl2br(htmlspecialchars($record['org_requirements'])) . '</p>';
        } else {
            echo '<p><i>No Requirements!</i></p>';
        }
        ?>
    </div>

    <!-- Files download list -->
    <?php
    if (isset($record['org_files']) && !empty($record['org_files'])) {
        $files = explode(',', $record['org_files']);
        $fileCount = 0;
        echo '<ul class="files-list" aria-label="Downloadable files">';
        foreach ($files as $file) {
            $file = trim($file);
            if (!empty($file)) {
                $serverFilePath = '../../admin/formsrc/org-files/' . $file;
                $fileUrl = '../../admin/formsrc/org-files/' . htmlspecialchars($file);
                if (file_exists($serverFilePath)) {
                    $fileCount++;
                    echo '<li><a href="' . $fileUrl . '" target="_blank" download>' . htmlspecialchars($file) . '</a></li>';
                }
            }
        }
        if ($fileCount === 0) {
            echo '<li style="color:#666; font-style: italic;">No downloadable files available at this time.</li>';
        }
        echo '</ul>';
    }
    ?>

    <!-- Apply button -->
    <?php
    if (isset($record['is_active']) && $record['is_active'] == 1 && isset($record['category']) && strtolower($record['category']) !== 'student-government') {
        echo '<div class="apply-btn-container">';
        echo '<a href="membership.php?id=' . $orgId . '" class="apply-btn" role="button" aria-label="Apply for membership">Apply</a>';
        echo '</div>';
    }
    ?>
</div>
  </div>

<div id="events-panel" role="tabpanel" aria-labelledby="events-tab" hidden>
</div>

<div id="officers-panel" role="tabpanel" aria-labelledby="officers-tab" hidden>
    <?php
    if (isset($_GET['id'])) {
        $orgId = intval($_GET['id']);

        // Step 1: Get distinct academic years for this organization, ordered descending (highest first)
        $queryYears = "
            SELECT DISTINCT ac_year
            FROM organization_officers
            WHERE org_id = :orgId
            ORDER BY ac_year DESC
        ";
        $stmtYears = $conn->prepare($queryYears);
        $stmtYears->execute(['orgId' => $orgId]);
        $academicYearsDesc = $stmtYears->fetchAll(PDO::FETCH_COLUMN);

        if (empty($academicYearsDesc)) {
            echo '<div style="text-align:center; margin-top:20px;">
                    <img src="../src/nopost.png" alt="No officers found" style="max-width:200px;"/>
                    <p>This Organization has no Officers!</p>
                  </div>';
        } else {
            // Highest year is the first in descending order
            $highestYear = $academicYearsDesc[0];

            // Sort ascending for button display
            $academicYearsAsc = $academicYearsDesc;
            sort($academicYearsAsc, SORT_STRING); // or SORT_NUMERIC if years are numeric
            ?>
            <style>
                .tabs {
                    display: flex;
                    justify-content: center;
                    margin-bottom: 20px;
                    flex-wrap: wrap;
                    gap: 10px;
                }
                .tab-button {
                    padding: 8px 16px;
                    border: 1px solid #ccc;
                    background-color: #f9f9f9;
                    cursor: pointer;
                    font-family: 'Outfit', sans-serif;
                    font-weight: 600;
                    border-radius: 4px;
                    transition: background-color 0.3s, border-color 0.3s;
                }
                .tab-button.active {
                    background-color: #0672a1;
                    color: white;
                    border-color: #0672a1;
                }
                .org-chart-wrapper {
                    text-align: center;
                    font-family: 'Outfit', sans-serif;
                }
                .officer {
                    display: inline-block;
                    margin: 15px;
                    width: 190px;
                    vertical-align: top;
                }
                .officer img {
                    width: 100px;
                    height: 100px;
                    border-radius: 50%;
                    background-color: #ccc; /* fallback */
                    margin-bottom: 1px;
                }
                .officer strong {
                    display: block;
                    font-weight: bold;
                    margin-bottom: 4px;
                    font-size: 14px;
                }
                .adviser {
                    margin-bottom: 30px;
                }
                .tab-content {
                    display: none;
                }
                .tab-content.active {
                    display: block;
                }
            </style>

            <!-- Label above buttons -->
            <div style="text-align: center; font-family: 'Outfit', sans-serif; font-weight: 700; margin-bottom: 8px; font-size: 16px;">
                ACADEMIC YEAR
            </div>

            <div class="tabs" role="tablist" aria-label="Academic Year Tabs">
                <?php
                foreach ($academicYearsAsc as $year) {
                    $activeClass = ($year === $highestYear) ? 'active' : '';
                    $tabId = 'tab-' . str_replace([' ', '-'], '_', $year);
                    $contentId = 'content-' . str_replace([' ', '-'], '_', $year);
                    echo "<button class='tab-button $activeClass' role='tab' aria-selected='" . ($activeClass ? 'true' : 'false') . "' aria-controls='$contentId' id='$tabId' data-year='$year'>$year</button>";
                }
                ?>
            </div>

            <?php
            // Render tab contents in descending order (highest first)
            foreach ($academicYearsDesc as $year) {
                $contentActiveClass = ($year === $highestYear) ? 'active' : '';

                // Fetch officers for this academic year
                $query = "
                    SELECT position, officer_fname, officer_mname, officer_lname, officer_photo, gender
                    FROM organization_officers
                    WHERE org_id = :orgId AND ac_year = :acYear
                    ORDER BY officer_id ASC
                ";
                $stmt = $conn->prepare($query);
                $stmt->execute(['orgId' => $orgId, 'acYear' => $year]);
                $officersData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Separate advisers and officers
                $advisers = [];
                $officers = [];

                foreach ($officersData as $row) {
                    $fullName = $row['officer_fname'];
                    if (!empty($row['officer_mname'])) {
                        $fullName .= ' ' . $row['officer_mname'];
                    }
                    $fullName .= ' ' . $row['officer_lname'];

                    if (!empty($row['officer_photo'])) {
                        $photo = $row['officer_photo'];
                    } else {
                        $photo = (strtolower($row['gender']) === 'm') ? 'maleofficers.png' : 'femaleofficers.png';
                    }

                    $officerData = [
                        'position' => $row['position'],
                        'fullName' => $fullName,
                        'photo' => $photo,
                    ];

                    if (strtolower($row['position']) === 'adviser') {
                        $advisers[] = $officerData;
                    } else {
                        $officers[] = $officerData;
                    }
                }

                $contentId = 'content-' . str_replace([' ', '-'], '_', $year);
                ?>
                <div id="<?php echo $contentId; ?>" class="tab-content <?php echo $contentActiveClass; ?>" role="tabpanel" aria-labelledby="tab-<?php echo str_replace([' ', '-'], '_', $year); ?>">
                    <div class="org-chart-wrapper">
                        <?php if (!empty($advisers)): ?>
                            <?php foreach ($advisers as $adviser): ?>
                                <div class="officer adviser">
                                    <img src="../../admin/src/officers-photos/<?php echo htmlspecialchars($adviser['photo']); ?>" alt="Adviser Photo" />
                                    <strong><?php echo htmlspecialchars($adviser['fullName']); ?></strong>
                                    <small><?php echo htmlspecialchars($adviser['position']); ?></small>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <div>
                            <?php foreach ($officers as $officer): ?>
                                <div class="officer">
                                    <img src="../../admin/src/officers-photos/<?php echo htmlspecialchars($officer['photo']); ?>" alt="Officer Photo" />
                                    <strong><?php echo htmlspecialchars($officer['fullName']); ?></strong>
                                    <small><?php echo htmlspecialchars($officer['position']); ?></small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    } else {
        echo "Organization ID not specified.";
    }
    ?>

    <script>
        // JavaScript to handle tab switching
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                const year = button.getAttribute('data-year');

                // Remove active class from all buttons and set aria-selected false
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active');
                    btn.setAttribute('aria-selected', 'false');
                });

                // Add active class to clicked button and set aria-selected true
                button.classList.add('active');
                button.setAttribute('aria-selected', 'true');

                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });

                // Show the selected tab content
                const contentId = 'content-' + year.replace(/[\s-]/g, '_');
                const content = document.getElementById(contentId);
                if (content) {
                    content.classList.add('active');
                }
            });
        });
    </script>
</div>


<div id="members-panel" role="tabpanel" aria-labelledby="members-tab" hidden>
    <?php
    if (isset($_GET['id'])) {
        $orgId = intval($_GET['id']);

        $query = "
            SELECT membership_id, first_name, middle_name, last_name, photo, course
            FROM memberships
            WHERE org_id = :orgId AND status = 'approve'
            ORDER BY membership_id ASC
        ";

        $stmt = $conn->prepare($query);
        $stmt->execute(['orgId' => $orgId]);
        $membersData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($membersData)) {
            echo '<div style="text-align:center; margin-top:20px;">
                    <img src="../src/nopost.png" alt="No members found" style="max-width:200px;"/>
                    <p>This Organization has no approved members!</p>
                  </div>';
        } else {
            $members = [];  // All approved members in one array
            $courseMap = [
                'bsit' => 'BSIT',
                'bsa' => 'BSA',
                'bsed' => 'BSED',
                'beed' => 'BEED',
                'bshm' => 'BSHM',
                'bsoa' => 'BSOA'
            ];
            foreach ($membersData as $row) {
                $fullName = $row['first_name'];
                if (!empty($row['middle_name'])) {
                    $fullName .= ' ' . $row['middle_name'];
                }
                $fullName .= ' ' . $row['last_name'];

                if (!empty($row['photo'])) {
                    $photo = $row['photo'];
                } else {
                    $photo = '../src/alt-photo-profile.png';
                }
                // Map course abbreviation to full form
                $rawCourse = $row['course'] ?? '';
                $displayCourse = 'N/A';  // Default fallback
                if (!empty($rawCourse)) {
                    $lowercaseCourse = strtolower(trim($rawCourse));
                    $displayCourse = $courseMap[$lowercaseCourse] ?? $rawCourse; 
                }

                $memberData = [
                    'fullName' => $fullName,
                    'course' => $displayCourse, 
                    'photo' => $photo,
                ];

                $members[] = $memberData;
            }

            $stmt = null;
            ?>

            <style>
                .members-wrapper {
                    text-align: center;
                    font-family: 'Outfit', sans-serif;
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));  /* Responsive grid: Adjust minmax for card width */
                    gap: 20px;
                    padding: 20px;
                }
                .member {
                    display: inline-block;
                    margin: 10px auto;
                    width: 140px;
                    vertical-align: top;
                    text-align: center;
                }
                .member img {
                    width: 80px;
                    height: 80px;
                    border-radius: 50%;
                    background-color: #ccc; /* Fallback for broken images */
                    margin-bottom: 8px;
                    object-fit: cover;  /* Ensures photos fit nicely */
                }
                .member strong {
                    display: block;
                    font-weight: bold;
                    margin-bottom: 4px;
                    font-size: 12px;
                    word-wrap: break-word;
                }
                .member small {
                    display: block;
                    font-size: 10px;
                    color: #666;
                    margin-top: 2px;
                }
            </style>
            <div class="members-wrapper">
                <?php foreach ($members as $member): ?>
                    <div class="member">
                        <img src="../src/membership/<?php echo htmlspecialchars($member['photo']); ?>" alt="Member Photo" />
                        <strong><?php echo htmlspecialchars($member['fullName']); ?></strong>
                        <small><?php echo htmlspecialchars($member['course']); ?></small>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php
        }
    } else {
        echo "Organization ID not specified.";
    }
    ?>
</div>

  </section>
</main>
<script>
  const tabs = document.querySelectorAll('.tab');
  const panels = document.querySelectorAll('[role="tabpanel"]');
  const homepageButton = document.getElementById('back-btn');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      // Deactivate all tabs and hide all panels
      tabs.forEach(t => {
        t.classList.remove('active');
        t.setAttribute('aria-selected', 'false');
        t.setAttribute('tabindex', '-1');
      });
      panels.forEach(panel => {
        panel.hidden = true;
      });

      // Activate clicked tab and show corresponding panel
      tab.classList.add('active');
      tab.setAttribute('aria-selected', 'true');
      tab.setAttribute('tabindex', '0');
      const panel = document.getElementById(tab.getAttribute('aria-controls'));
      panel.hidden = false;
      tab.focus();
    });

    tab.addEventListener('keydown', e => {
      let index = Array.from(tabs).indexOf(e.currentTarget);
      if (e.key === 'ArrowRight') {
        e.preventDefault();
        let nextIndex = (index + 1) % tabs.length;
        tabs[nextIndex].click();
      } else if (e.key === 'ArrowLeft') {
        e.preventDefault();
        let prevIndex = (index - 1 + tabs.length) % tabs.length;
        tabs[prevIndex].click();
      }
    });
  });

  // Add homepage button event listener outside the tabs loop
  homepageButton.addEventListener('click', function(event) {
    event.preventDefault();
    window.location.href = '../home.php';
  });

</script>
</body>
</html>