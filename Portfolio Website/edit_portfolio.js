function enableEdit() {
    document.getElementById('about-text').style.display = 'none';
    document.getElementById('about-edit').style.display = 'block';
    document.getElementById('edit-about-button').style.display = 'none';
}
//saving portfolio page about section script
function savePortfolioAbout(portfolioID) {
    let aboutText = document.getElementById('about-input').value;
    
    let formData = new FormData();
    formData.append("update_about", true);
    formData.append("about", aboutText);

    fetch("", { 
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === "success") {
            document.getElementById('about-text').innerHTML = aboutText.replace(/\n/g, '<br>');
            document.getElementById('about-text').style.display = 'block';
            document.getElementById('about-edit').style.display = 'none';
            document.getElementById('edit-about-button').style.display = 'inline-block';
        } else {
            alert("Failed to update. Try again.");
        }
    });
}
//saving project page about section script
function saveProjectAbout(projectID) {
    let aboutText = document.getElementById('about-input').value;
    
    let formData = new FormData();
    formData.append("update_about", true);
    formData.append("about", aboutText);

    fetch("", { 
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === "success") {
            document.getElementById('about-text').innerHTML = aboutText.replace(/\n/g, '<br>');
            document.getElementById('about-text').style.display = 'block';
            document.getElementById('about-edit').style.display = 'none';
            document.getElementById('edit-about-button').style.display = 'inline-block';
        } else {
            alert("Failed to update. Try again.");
        }
    });
}
//opening and closing modal script
function openPopup(modalId) {
    document.getElementById(modalId).style.display = "block";
}

function closePopup(modalId) {
    document.getElementById(modalId).style.display = "none";
}
//scrolling carousel script
function scrollCarousel(galleryId, direction) {
    let container = document.getElementById(galleryId);
    container.scrollBy({ left: direction * 300, behavior: 'smooth' });
}

//javascript for copying current web address url
function copyCurrentURL() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        alert("Shareable link copied to clipboard!");
    }).catch(err => {
        console.error("Failed to copy: ", err);
    });
}

