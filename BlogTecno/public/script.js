document.addEventListener("DOMContentLoaded", () => {
    // Cargar datos del perfil
    if (window.location.pathname.includes("profile.html")) {
        fetch("src/controllers/profile.php")
            .then((response) => response.json())
            .then((data) => {
                document.getElementById("display_name").value = data.display_name || "";
                document.getElementById("bio").value = data.bio || "";
                const profilePicture = data.profile_picture || "default-profile.png";
                document.getElementById("profile-picture-preview").src = profilePicture;
                document.getElementById("summary-picture").src = profilePicture;
                document.getElementById("summary-name").textContent = data.display_name || "Nombre de Perfil";
                document.getElementById("summary-bio").textContent = data.bio || "Aquí aparecerá tu descripción.";
            })
            .catch((error) => console.error("Error al cargar el perfil:", error));
    }

    // Vista previa de la foto de perfil
    const profilePictureInput = document.getElementById("profile_picture");
    const profilePicturePreview = document.getElementById("profile-picture-preview");
    const summaryPicture = document.getElementById("summary-picture");

    profilePictureInput?.addEventListener("change", () => {
        const file = profilePictureInput.files[0];
        if (file && file.type.startsWith("image/")) {
            const previewUrl = URL.createObjectURL(file);
            profilePicturePreview.src = previewUrl;
            summaryPicture.src = previewUrl;
        }
    });

    // Sincronizar nombre y descripción con el resumen
    const displayNameInput = document.getElementById("display_name");
    const bioInput = document.getElementById("bio");
    const summaryName = document.getElementById("summary-name");
    const summaryBio = document.getElementById("summary-bio");

    displayNameInput?.addEventListener("input", () => {
        summaryName.textContent = displayNameInput.value || "Nombre de Perfil";
    });

    bioInput?.addEventListener("input", () => {
        summaryBio.textContent = bioInput.value || "Aquí aparecerá tu descripción.";
    });

    // Manejar interacciones desplegables
    document.querySelectorAll(".reaction-btn").forEach((button) => {
        button.addEventListener("click", () => {
            const menu = button.nextElementSibling;
            menu.style.display = menu.style.display === "block" ? "none" : "block";
        });
    });

    // Manejar comentarios
    document.querySelectorAll(".comment-form").forEach((form) => {
        form.addEventListener("submit", (event) => {
            event.preventDefault();
            const articleId = form.closest("li").dataset.articleId;
            const commentText = form.querySelector("textarea").value.trim();
            const commentList = form.previousElementSibling;

            if (commentText) {
                fetch("src/controllers/comments.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ articleId, comment: commentText })
                })
                    .then((response) => response.json())
                    .then((data) => {
                        const newComment = document.createElement("li");
                        newComment.innerHTML = `
                            <div class="comment-profile">
                                <img src="${data.profilePicture}" alt="${data.username}" class="profile-pic">
                                <span class="comment-username">${data.username}</span>
                                <span class="comment-timestamp">${data.timestamp}</span>
                            </div>
                            <p>${data.comment}</p>
                        `;
                        commentList.appendChild(newComment);
                        form.reset();
                    })
                    .catch((error) => console.error("Error al enviar el comentario:", error));
            }
        });
    });
});
