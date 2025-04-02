/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.scss";
import 'use-bootstrap-tag/dist/use-bootstrap-tag.css';

document.querySelectorAll(".like-button").forEach((button) => {
	button.addEventListener("click", function () {
		let type = this.getAttribute("data-type");
		let id = this.getAttribute("data-id");
		let $likeIcon = this.querySelector(".like");
		
		fetch(`/like/${type}/${id}`, { method: "POST" }).then((response) => {
			switch (response.status) {
				case 201:
					$likeIcon.className = "like fa-solid fa-heart";
					break;
					case 204:
						$likeIcon.className = "like fa-regular fa-heart";
						break;
						case 403:
					alert("Please sign in to put a like");
				}
		});
	});
});