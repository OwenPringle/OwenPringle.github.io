    <!--Start of photos and videos section-->
    <div class="section" id="about-portfolios">
        <div class="section-writing">
            <h2>Portfolio Projects</h2>

            <!-- Display User Images -->
            <div class="media-section">
                <h3>Images</h3>
                <div class="image-gallery">
                    <?php
                    $imageQuery = "SELECT * FROM user_images WHERE portfolioid = $portfolioID";
                    $imageResult = mysqli_query($link, $imageQuery);
                    while ($imageRow = mysqli_fetch_assoc($imageResult)) {
                        echo "<div class='image-item'>";
                        echo "<img src='uploads/{$imageRow['image_url']}' alt='" . htmlspecialchars($imageRow['title']) . "'>";
                        echo "<p>" . htmlspecialchars($imageRow['description']) . "</p>";
                        echo "</div>";
                    }
                    ?>
                    </div>
                </div>
                
                <!-- Display User Videos -->
                 <div class="media-section">
                    <h3>Videos</h3>
                    <div class="video-gallery">
                        <?php
                        $videoQuery = "SELECT * FROM user_videos WHERE portfolioid = $portfolioID";
                        $videoResult = mysqli_query($link, $videoQuery);
                        while ($videoRow = mysqli_fetch_assoc($videoResult)) {
                            echo "<div class='video-item'>";
                            echo "<video controls width='320'>";
                            echo "<source src='uploads/{$videoRow['video_url']}' type='video/mp4'>";
                            echo "Your browser does not support the video tag.";
                            echo "</video>";
                            echo "<p>" . htmlspecialchars($videoRow['description']) . "</p>";
                            echo "</div>";
                        }
                        ?>
                        </div>
                    </div>

                    <!-- Start of uploading videos/images section -->
                     <?php if ($userID == $portfolioCreatorID): ?>                        
                        <div class="upload-section">
                            <h3>Upload Images or Videos</h3>
                            <form action="upload_media.php" method="POST" enctype="multipart/form-data">
                                
                                <input type="hidden" name="portfolioid" value="<?php echo $portfolioID; ?>">

                                <label for="mediaType">Select Media Type:</label>
                                <select name="mediaType" required>
                                    <option value="image">Image</option>
                                    <option value="video">Video</option>
                                </select>
        
                                <label for="title" >Media Title</label>
                                <input type="text" name="title" required placeholder="Video Title">
                                <label for="description">Description:</label>
                                <textarea name="description" rows="3"></textarea>
                                <label for="file">Upload File:</label>
                                <input type="file" name="file" accept="image/*,video/*" required>
                                <button type="submit" name="upload">Upload</button>
                            </form>
                        </div>
                        <?php endif; ?>
                        <!-- End of uploading videos/images section -->

                </div>
            </div>
    <!--End of photos and videos section-->