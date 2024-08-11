<?php
include 'Connection/Connect.php';
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", true);
header("Pragma: no-cache");
session_start();


// Fetch forum details
$forum_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = isset($_GET['user']) ? intval($_GET['user']) : 0;
$name = isset($_GET['forum']) ? trim($_GET['forum']) : '';

if ($forum_id == 0 || empty($name)) {
    die("Invalid forum ID or name.");
}

// Fetch forum details from the database
$stmt = $conn->prepare("SELECT * FROM forums WHERE `name` = :name");
$stmt->bindParam(':name', $name, PDO::PARAM_STR);
$stmt->execute();
$forum = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$forum) {
    die("Forum not found");
}

$message = '';
$message_type = '';
$clear_form = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
    // Get and sanitize comment
    $comment_text = trim($_POST['comment']);

    if (!empty($comment_text)) {
        // Insert comment into the database
        $insertStmt = $conn->prepare("INSERT INTO comments (user_id, forum_id, comment) VALUES (:user_id, :forum_id, :comment)");
        $insertStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $insertStmt->bindParam(':forum_id', $forum_id, PDO::PARAM_INT);
        $insertStmt->bindParam(':comment', $comment_text, PDO::PARAM_STR);

        if ($insertStmt->execute()) {
            $message = 'Comment added successfully!';
            $message_type = 'success';
            $clear_form = true; // Set flag to clear the form
            header("Location: comments.php?forum=$name&id=$forum_id&user=$user_id");
            exit();
        } else {
            $message = 'Failed to add comment.';
            $message_type = 'danger';
        }
    } else {
        $message = 'Comment cannot be empty.';
        $message_type = 'warning';
    }
}

// Fetch user's image and name
$stmt = $conn->prepare("SELECT * FROM `user_info` WHERE sno = :id");
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user_info = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch comments for the forum with user details
$commentsStmt = $conn->prepare("
        SELECT comments.*, user_info.username, user_info.image 
        FROM comments 
        JOIN user_info ON comments.user_id = user_info.sno 
        WHERE comments.forum_id = :forum_id 
        ORDER BY comments.sno DESC
    ");
$commentsStmt->bindParam(':forum_id', $forum_id, PDO::PARAM_INT);
$commentsStmt->execute();
$comments = $commentsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/comments.css">
    <style>
        
    </style>
</head>

<body>
    <div class="container">
        <div class="spinner-container" id="spinner">
            <div class="spinner-border text-primary" role="status">

            </div>
        </div>
        <div class="container mt-5">
            <!-- Alert -->
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Forum Card -->
            <div class="card mb-4 forum-card">
                <div class="card-header forum-card-header">
                    <a href="Forum.php?user=<?php echo $user_id; ?>">
                        <h3 class="mb-0"><?php echo htmlspecialchars($forum['name']); ?></h3>
                    </a>
                </div>
                <div class="card-body">
                    <p class="card-text"><?php echo htmlspecialchars($forum['desc1']); ?></p>
                </div>
            </div>

            <!-- Comment Form -->
            <form action="" method="POST">
                <div class="form-group">
                    <label for="comment">Comment</label>
                    <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="Your comment"
                        required><?php echo $clear_form ? '' : ''; ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

            <hr>

            <!-- Display Comments -->
            <div id="comments-section">
                <?php if (empty($comments)): ?>
                    <p class="text-muted fs-1 fw-bold text-secondary">No comments yet! Write a comment below.</p>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment-box">
                            <div class="comment-header">
                                <img src="Images/<?php echo htmlspecialchars($comment['image']); ?>" alt="User Image"
                                    class="comment-avatar">
                                <div>
                                    <h5 class="mb-1"><?php echo htmlspecialchars($comment['username']); ?></h5>
                                    <small class="text-muted">Posted on
                                        <?php echo date('F j, Y', strtotime($comment['created_at'])); ?></small>
                                </div>
                            </div>
                            <p class="mb-1"><?php echo htmlspecialchars($comment['comment']); ?></p>
                            <div class="comment-actions">
                                <?php if ($comment['user_id'] == $user_id): ?>
                                    <a href="" class="edit" data-comment="<?php echo htmlspecialchars($comment['comment']); ?>"
                                        data-id="<?php echo $comment['sno'] ?>"><i class="fas fa-edit"></i></a>
                                    <a href="delete_comment.php" class="delete" data-id="<?php echo $comment['sno'] ?>"><i
                                            class="fas fa-trash-alt"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
        include_once 'edit_modal.php';
        ?>


        <script src="JS/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
<script>
    $(document).ready(function () {
        $("#spinner").fadeOut();
    })
    document.addEventListener('DOMContentLoaded', function () {

        if (<?php echo isset($_SESSION['success']) && $_SESSION['success'] ? 'true' : 'false'; ?>) {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Comment Edited Successfully',
                showConfirmButton: false,
                timer: 1500
            });
            <?php unset($_SESSION['success']); ?>
        }
    });
    const delButtons = document.querySelectorAll(".delete");
    delButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            const id = button.getAttribute('data-id');
            const forum = encodeURIComponent("<?php echo $name; ?>");
            const fid = "<?php echo $forum_id; ?>";
            const user = "<?php echo $user_id; ?>";
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `delete_comment.php?id=${id}&forum=${forum}&fid=${fid}&user=${user}`;
                }
            });
        });
    });

    const editButtons = document.querySelectorAll(".edit");

    editButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            const id = button.getAttribute('data-id');
            const commentText = button.getAttribute('data-comment');
            const forum = encodeURIComponent("<?php echo $name; ?>");
            const fid = "<?php echo $forum_id; ?>";
            const user = "<?php echo $user_id; ?>";
            document.getElementById('editCommentId').value = id;
            document.getElementById('f_name').value = forum;
            document.getElementById('fid').value = fid;
            document.getElementById('uid').value = user;
            document.getElementById('editCommentText').innerText = commentText;


            $('#editCommentModal').modal('show');
        });
    });
</script>