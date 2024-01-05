<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Like Button</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" 
        referrerpolicy="no-referrer" />

    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f2f2f2; /* Set background color */
        }

        a {
            font-size: 100px;
            text-decoration: none;
            color: #3498db; /* Set the blue color */
            transition: color 0.3s ease;
            cursor: pointer;
        }

        a.disabled {
            color: #bdc3c7; /* Set a disabled color */
            cursor: not-allowed;
        }

        a.clicked {
            color: #2980b9; /* Change color when clicked to a darker blue */
            animation: goUp 0.5s ease;
        }

        @keyframes goUp {
            0% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div>
        <a id="likeButton" href="#" type="button" onclick="like()">
            <center>
                <i class="far fa-thumbs-up"></i><br>
                <span id="likeCount"></span> Likes
            </center>
        </a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" 
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            // set that the user hasn't  liked in the current session
            var hasLiked = sessionStorage.getItem('hasLiked') === 'false';

            // Get initial like count on page load
            updateLikeCount();

            // Handle like button click
            $('#likeButton').click(function() {
                // Check if the user has already liked in the current session
                if (hasLiked) {
                    alert('You can only like once!');
                    return;
                }

                // Make an Ajax request to increment the like count
                $.ajax({
                    type: 'POST',
                    url: '/Like/like.php', // PHP script to handle the like logic
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.success) {
                            // Update the like count on the page
                            $('#likeCount').text(response.likeCount);

                            // If further likes are disabled, add the 'disabled' class
                            if (response.likeCount === -1) {
                                $('#likeButton').addClass('disabled').attr('onclick', '').css('cursor', 'not-allowed');
                            } else {
                                // Mark the user as having liked in the current session
                                sessionStorage.setItem('hasLiked', 'true');
                                hasLiked = true; // Update the local variable

                                // Add class for color change and animation
                                $('#likeButton').addClass('clicked');

                                // Remove the class after a delay
                                setTimeout(function() {
                                    $('#likeButton').removeClass('clicked');
                                }, 500);
                            }
                            updateLikeCount();
                            hasLiked == 'true'; // set the session to true to make sure the user has clicked the like btn
                        } else {
                            alert('Error: ' + response.message || 'Invalid response from the server');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax request failed. Status:', status, 'Error:', error);
                        alert('Error: Unable to process the request');
                    }
                });
            });

            // Function to update the like count on the page
            function updateLikeCount() {
                $.ajax({
                    type: 'GET',
                    url: '/Like/get_likes.php', // PHP script to get the current like count
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.success) {
                            // Update the like count on the page
                            $('#likeCount').text(response.likeCount);

                            // If further likes are disabled, add the 'disabled' class
                            if (response.likeCount === -1) {
                                $('#likeButton').addClass('disabled').attr('onclick', '').css('cursor', 'not-allowed');
                            }
                        } else {
                            alert('Error: ' + response.message || 'Invalid response from the server');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax request failed. Status:', status, 'Error:', error);
                        alert('Error: Unable to process the request');
                    }
                });
            }
        });
    </script>
</body>
</html>
