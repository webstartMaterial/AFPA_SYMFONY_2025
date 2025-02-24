import * as bootstrap from 'bootstrap'; // Important : Import de Bootstrap en tant que module
window.bootstrap = bootstrap; // Rendre Bootstrap accessible globalement

// import 'bootstrap/dist/js/bootstrap.bundle.min'; // Ce fichier inclut déjà Popper.js
import 'bootstrap/dist/css/bootstrap.min.css';

import '@fortawesome/fontawesome-free/css/all.min.css';
import '@fortawesome/fontawesome-free/js/all.js';
import './styles/app.scss';

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */


document.addEventListener("DOMContentLoaded", function () {
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

    document.querySelectorAll(".article-info").forEach(button => {

        button.addEventListener('click', function() {

            console.log("click");

            let articleId = this.getAttribute('data-id');

            console.log(articleId);

            fetch(`/article/${articleId}/json`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    console.log(data);

                    // Remplir les champs de la pop-up avec les données reçues
                    document.querySelector("#infoModal .title").innerText = data.title;
                    document.querySelector("#infoModal img").src = data.image;
                    document.querySelector("#infoModal .description").innerText = data.description;
                    document.querySelector("#infoModal .category").innerText = data.category;
                    document.querySelector("#infoModal .price").innerText = `Prix : ${data.price} €`;
                    document.querySelector("#infoModal .stock").innerText = data.stock;

                    // Sélectionne le formulaire et met à jour son action
                    let form = document.querySelector("#infoModal form");
                    form.setAttribute("action", `/cart/${articleId}`);
                    
                    // Afficher la modale
                    let modal = new bootstrap.Modal(document.getElementById("infoModal"));
                    modal.show();

                }).catch(
                    error => console.log("Erreur lors du chagement de l'article", error)
                )

        });

    });

});